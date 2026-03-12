<?php

namespace App\Controllers;

use App\Models\NewsModel;
use App\Models\CategoryModel;

class NewsController extends BaseController
{
    /**
     * కేటగిరీ ఆధారంగా వార్తలను పొందడం (Enhanced with Reporter Details)
     */
    public function category($id) {
        $newsModel = new \App\Models\NewsModel();
        $categoryModel = new \App\Models\CategoryModel();

        // 1. కేటగిరీ వివరాలు పొందడం
        $category = $categoryModel->find($id);
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("కేటగిరీ లభించలేదు.");
        }

        // 2. జాయిన్ క్వెరీ: కేటగిరీ వివరాలతో పాటు రిపోర్టర్ (User) ప్రొఫైల్ వివరాలను కూడా పొందుతున్నాను
        $news_list = $newsModel->select('
        news.*, 
        categories.name as category_name, 
        categories.type as category_type, 
        users.display_name as reporter_name, 
        users.profile_pic as reporter_image, 
        users.area_coverage as reporter_area,
        districts.name as district_name,
        mandals.name as mandal_name
        ')
        ->join('categories', 'categories.id = news.category_id')
        ->join('users', 'users.id = news.author_id', 'left')
        ->join('categories as districts', 'districts.id = news.district_id', 'left') // జిల్లా వివరాల కోసం
        ->join('categories as mandals', 'mandals.id = news.mandal_id', 'left')    // మండల వివరాల కోసం
        ->where('news.category_id', $id)
        ->where('news.status', 1)
        // SQL Injection నివారించడానికి మరియు క్లీన్ ఆర్డరింగ్ కోసం ఈ కింది పద్ధతి వాడండి
        ->orderBy('COALESCE(news.published_at, news.created_at) DESC', '', false)
        ->paginate(12);

        $data = [
            'category'      => $category,
            'news_list'     => $news_list,
            'pager'         => $newsModel->pager,
            'categories'    => $categoryModel->where('IsActice', 1)->findAll(), 
            'title'         => $category['name'] . " వార్తలు - NToday",
            'meta_desc'     => "NTodayలో " . $category['name'] . " కి సంబంధించిన తాజా వార్తలు మరియు అప్‌డేట్స్ చూడండి."
        ];

        return view('frontend/category_view', $data);
    }

    /**
     * ట్రెండింగ్ వార్తలు (Enhanced with Reporter & Date Logic)
     */
    public function trending() {
    $newsModel = new \App\Models\NewsModel();
    $categoryModel = new \App\Models\CategoryModel();
    $bannerModel = new \App\Models\BannersModel(); // బానర్ మోడల్ యాడ్ చేసాను

    // గత 7 రోజుల్లో ట్రెండింగ్‌లో ఉన్న వార్తలను పొందడం
    $trending_news = $newsModel->select('news.*, categories.name as category_name, users.display_name as reporter_name, users.area_coverage')
                               ->join('categories', 'categories.id = news.category_id', 'left')
                               ->join('users', 'users.id = news.author_id', 'left')
                               ->where('news.status', 1)
                               ->where('news.published_at >=', date('Y-m-d', strtotime('-7 days'))) 
                               ->orderBy('news.view_count', 'DESC')
                               ->paginate(15);

    $data = [
        'trending_news' => $trending_news,
        'pager'         => $newsModel->pager,
        'categories'    => $categoryModel->where('IsActice', 1)->findAll(),
        'title'         => 'ట్రెండింగ్ వార్తలు - NToday',
        'meta_desc'     => 'NTodayలో ప్రస్తుతం ట్రెండింగ్‌లో ఉన్న తాజా మరియు ముఖ్యమైన వార్తలు ఇక్కడ చూడండి.',
        // సైడ్‌బార్ ప్రకటనలను RANDOM పద్ధతిలో ఫెచ్ చేయడం
        'sidebar_ads'   => $bannerModel->where(['position' => 'sidebar_ad', 'status' => 1])->orderBy('id', 'RANDOM')->findAll(3)
    ];

    return view('frontend/trending_view', $data);
}

    // వార్తను సృష్టించే పేజీ (Form Display) - యధావిధిగా ఉంచబడింది
    public function create()
    {
        $categoryModel = new CategoryModel();
        $data['main_categories'] = $categoryModel->where('type', 'main')->findAll();
        $data['states'] = $categoryModel->where('type', 'state')->where('IsActice', 1)->findAll();
        $data['title'] = "నూతన వార్త ప్రచురణ";
        return view('reporter/add_news', $data);
    }

    /**
     * వార్తను భద్రపరచడం (Enhanced Data Packaging)
     */
    public function store()
    {
        $newsModel = new NewsModel();
        $rules = [
            'title'   => 'required|min_length[10]',
            'content' => 'required',
            'category_id' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $croppedImage = $this->request->getPost('cropped_image');
        $newName = "";

        if (!empty($croppedImage) && strpos($croppedImage, 'data:image') !== false) {
            $image_parts = explode(";base64,", $croppedImage);
            $image_base64 = base64_decode($image_parts[1]);
            $newName = time() . '_news.jpg';
            $path = FCPATH . 'uploads/news/';
            if (!is_dir($path)) { mkdir($path, 0777, true); }
            file_put_contents($path . $newName, $image_base64);
        } else {
            $file = $this->request->getFile('featured_image');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/news', $newName);
            }
        }

        $title = $this->request->getPost('title');
        $rawContent = $this->request->getPost('content');
        $summary = substr(strip_tags($rawContent), 0, 250);

        $data = [
            'title'            => $title,
            'slug'             => url_title($title, '-', true) . '-' . time(),
            'content'          => $rawContent,
            'summary'          => $summary,
            'category_id'      => $this->request->getPost('category_id'),
            'sub_category_id'  => $this->request->getPost('sub_category_id') ?: null,
            'state_id'         => $this->request->getPost('state_id') ?: null,
            'district_id'      => $this->request->getPost('district_id') ?: null,
            'mandal_id'        => $this->request->getPost('mandal_id') ?: null,
            'village_name'     => $this->request->getPost('village_name'),
            'author_id'        => session()->get('user_id') ?? 1,
            'featured_image'   => $newName,
            'meta_keywords'    => $this->request->getPost('meta_keywords'),
            'meta_description' => $this->request->getPost('meta_description') ?: substr(strip_tags($summary), 0, 160),
            'is_breaking_news' => $this->request->getPost('is_breaking_news') ? 1 : 0,
            'status'           => 0,
            'view_count'       => 0,
            'published_at'     => null 
        ];

        if ($newsModel->save($data)) {
            return redirect()->to('/reporter/dashboard')->with('success', 'వార్త విజయవంతంగా పంపబడింది. అడ్మిన్ అప్రూవల్ కోసం వేచి ఉండండి.');
        } else {
            return redirect()->back()->withInput()->with('error', 'వార్తను సేవ్ చేయడంలో లోపం తలెత్తింది.');
        }
    }

    // వార్తను ఆమోదించడం
    public function approve($id)
    {
        $newsModel = new NewsModel();
        $newsModel->update($id, [
            'status'       => 1,
            'published_at' => date('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'వార్త విజయవంతంగా ప్రచురించబడింది.');
    }

    // వార్తను తిరస్కరించడం
    public function reject($id)
    {
        $newsModel = new NewsModel();
        $newsModel->update($id, ['status' => 2]);
        return redirect()->back()->with('error', 'వార్త తిరస్కరించబడింది.');
    }

    // అన్ని వార్తల నిర్వహణ (Admin)
    public function index()
    {
        $newsModel = new NewsModel();
        $data['news'] = $newsModel->select('news.*, categories.name as category_name, users.display_name as reporter_name')
                                  ->join('categories', 'categories.id = news.category_id', 'left')
                                  ->join('users', 'users.id = news.author_id', 'left')
                                  ->orderBy('news.created_at', 'DESC')
                                  ->findAll();
        return view('admin/news/index', $data);
    }
}