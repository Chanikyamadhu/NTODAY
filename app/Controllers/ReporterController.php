<?php

namespace App\Controllers;

use App\Models\NewsModel;
use App\Models\CategoryModel;
use App\Models\UserModel;

class ReporterController extends BaseController
{
    /**
     * రిపోర్టర్ డాష్‌బోర్డ్
     */
    public function index()
    {
        $newsModel = new NewsModel();
        $userId = session()->get('user_id');

        if (!$userId) return redirect()->to('login');

        $data = [
            'my_news' => $newsModel->where('author_id', $userId)
                                   ->orderBy('created_at', 'DESC')
                                   ->findAll(),
            'published_count' => $newsModel->where(['author_id' => $userId, 'status' => 1])->countAllResults(),
            'pending_count'   => $newsModel->where(['author_id' => $userId, 'status' => 0])->countAllResults(),
            'title'           => 'రిపోర్టర్ డాష్‌బోర్డ్'
        ];

        return view('reporter/dashboard', $data); 
    }

    /**
     * కొత్త వార్త రాసే పేజీ
     */
    public function create()
    {
        $categoryModel = new CategoryModel();
        
        // IsActice - మీ DB స్పెల్లింగ్ ప్రకారం ఇక్కడ వాడాను
        $all_cats = $categoryModel->whereIn('type', ['main', 'state'])
                                  ->where('IsActice', 1) 
                                  ->orderBy('name', 'ASC')
                                  ->findAll();

        $data = [
            'categories'      => $all_cats, 
            'main_categories' => array_values(array_filter($all_cats, function($c) { return $c['type'] == 'main'; })),
            'states'          => array_values(array_filter($all_cats, function($c) { return $c['type'] == 'state'; })),
            'title'           => "నూతన వార్త ప్రచురణ"
        ];
        return view('reporter/add_news', $data);
    }

    /**
     * వార్తను సేవ్ చేయడం
     */
    public function store()
    {
        $newsModel = new NewsModel();
        $userId = session()->get('user_id');

        // 1. Validation Rules
        $rules = [
            'title'       => 'required|min_length[10]',
            'content'     => 'required',
            'category_id' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'సరైన వివరాలు నమోదు చేయండి.');
        }

        // 2. Image Handling
        $croppedImage = $this->request->getPost('cropped_image');
        $imageName = "";

        if (!empty($croppedImage) && strpos($croppedImage, 'data:image') !== false) {
            $image_parts = explode(";base64,", $croppedImage);
            $imageName = time() . '_rep_news.jpg';
            $path = FCPATH . 'uploads/news/';
            if (!is_dir($path)) mkdir($path, 0777, true);
            file_put_contents($path . $imageName, base64_decode($image_parts[1]));
        }

        // 3. Slug Generation using Transliteration
        $title = $this->request->getPost('title');
        $englishTitle = $this->transliterateTelugu($title); 
        $slug = url_title($englishTitle, '-', true) . '-' . time();

        // 4. Data Preparation
        $data = [
            'author_id'        => $userId,
            'title'            => $title,
            'slug'             => $slug,
            'content'          => $this->request->getPost('content'),
            'summary'          => substr(strip_tags($this->request->getPost('content')), 0, 250),
            'category_id'      => $this->request->getPost('category_id'),
            'sub_category_id'  => $this->request->getPost('sub_category_id') ?: null,
            'state_id'         => $this->request->getPost('category_id') ?: null,
            'district_id'      => $this->request->getPost('district_id') ?: null,
            'mandal_id'        => $this->request->getPost('mandal_id') ?: null,
            'village_id'       => $this->request->getPost('village_id') ?: null,
            'featured_image'   => $imageName,
            'meta_keywords'    => $this->request->getPost('meta_keywords'),
            'meta_description' => $this->request->getPost('meta_description') ?: substr(strip_tags($title), 0, 160),
            'is_breaking_news' => $this->request->getPost('is_breaking_news') ? 1 : 0,
            'status'           => 0, // Always Pending for Review
        ];

        if ($newsModel->insert($data)) {
            return redirect()->to('reporter/dashboard')->with('success', 'వార్త విజయవంతంగా పంపబడింది.');
        }
        return redirect()->back()->withInput()->with('error', 'వార్త సేవ్ చేయడంలో సమస్య ఏర్పడింది.');
    }

    /**
     * తెలుగు అక్షరాలను ఇంగ్లీష్‌లోకి మార్చే ఫంక్షన్
     */
    private function transliterateTelugu($text) {
        $table = [
            'అ' => 'a', 'ఆ' => 'aa', 'ఇ' => 'i', 'ఈ' => 'ee', 'ఉ' => 'u', 'ఊ' => 'oo', 'ఋ' => 'ru',
            'ఎ' => 'e', 'ఏ' => 'ae', 'ఐ' => 'ai', 'ఒ' => 'o', 'ఓ' => 'oo', 'ఔ' => 'au',
            'క' => 'ka', 'ఖ' => 'kha', 'గ' => 'ga', 'ఘ' => 'gha', 'ఙ' => 'gna',
            'చ' => 'cha', 'ఛ' => 'chha', 'జ' => 'ja', 'ఝ' => 'jha', 'ఞ' => 'nya',
            'ట' => 'ta', 'ఠ' => 'tha', 'డ' => 'da', 'ఢ' => 'dha', 'ణ' => 'na',
            'త' => 'tha', 'థ' => 'thha', 'ద' => 'da', 'ధ' => 'dha', 'న' => 'na',
            'ప' => 'pa', 'ఫ' => 'pha', 'బ' => 'ba', 'భ' => 'bha', 'మ' => 'ma',
            'య' => 'ya', 'ర' => 'ra', 'ల' => 'la', 'వ' => 'va', 'శ' => 'sha', 'ష' => 'sha', 'స' => 'sa', 'హ' => 'ha', 'ళ' => 'la', 'క్ష' => 'ksha', 'ఱ' => 'ra',
            'ా' => 'aa', 'ి' => 'i', 'ీ' => 'ee', 'ు' => 'u', 'ూ' => 'oo', 'ృ' => 'ru', 'ె' => 'e', 'ే' => 'ae', 'ై' => 'ai', 'ొ' => 'o', 'ో' => 'oo', 'ౌ' => 'au', 'ం' => 'm', 'ః' => 'h'
        ];
        $text = str_replace(array_keys($table), array_values($table), $text);
        $text = preg_replace('/[^\x20\x30-\x39\x41-\x5a\x61-\x7a]/u', '', $text);
        return $text;
    }

    /**
     * వార్తను ఎడిట్ చేసే పేజీ
     */
    public function edit($id)
    {
        $newsModel = new NewsModel();
        $categoryModel = new CategoryModel();
        $userId = session()->get('user_id');

        $news = $newsModel->where(['id' => $id, 'author_id' => $userId])->first();
        if (!$news) {
            return redirect()->to('reporter/dashboard')->with('error', 'వార్త లభించలేదు.');
        }

        $all_cats = $categoryModel->whereIn('type', ['main', 'state'])->where('IsActice', 1)->findAll();

        $data = [
            'news'            => $news,
            'categories'      => $all_cats,
            'main_categories' => array_values(array_filter($all_cats, function($c) { return $c['type'] == 'main'; })),
            'states'          => array_values(array_filter($all_cats, function($c) { return $c['type'] == 'state'; })),
            'title'           => "వార్తను సవరించండి"
        ];

        return view('reporter/edit_news', $data);
    }

    /**
     * సవరించిన వార్తను అప్‌డేట్ చేయడం
     */
    public function update($id)
    {
        $newsModel = new NewsModel();
        $userId = session()->get('user_id');

        $news = $newsModel->where(['id' => $id, 'author_id' => $userId])->first();
        if (!$news) {
            return redirect()->to('reporter/dashboard')->with('error', 'అనుమతి లేదు.');
        }

        $croppedImage = $this->request->getPost('cropped_image');
        $imageName = $news['featured_image'];

        if (!empty($croppedImage) && strpos($croppedImage, 'data:image') !== false) {
            if (!empty($news['featured_image']) && file_exists(FCPATH . 'uploads/news/' . $news['featured_image'])) {
                unlink(FCPATH . 'uploads/news/' . $news['featured_image']);
            }
            $image_parts = explode(";base64,", $croppedImage);
            $imageName = time() . '_updated.jpg';
            file_put_contents(FCPATH . 'uploads/news/' . $imageName, base64_decode($image_parts[1]));
        }

        $data = [
            'id'               => $id,
            'title'            => $this->request->getPost('title'),
            'content'          => $this->request->getPost('content'),
            'category_id'      => $this->request->getPost('category_id'),
            'sub_category_id'  => $this->request->getPost('sub_category_id') ?: null,
            'district_id'      => $this->request->getPost('district_id') ?: null,
            'mandal_id'        => $this->request->getPost('mandal_id') ?: null,
            'village_id'       => $this->request->getPost('village_id') ?: null,
            'featured_image'   => $imageName,
            'meta_keywords'    => $this->request->getPost('meta_keywords'),
            'meta_description' => $this->request->getPost('meta_description'),
            'is_breaking_news' => $this->request->getPost('is_breaking_news') ? 1 : 0,
            'status'           => 0, // Moves back to pending after edit
        ];

        if ($newsModel->save($data)) {
            return redirect()->to('reporter/dashboard')->with('success', 'వార్త అప్‌డేట్ చేయబడింది.');
        }
        return redirect()->back()->withInput()->with('error', 'అప్‌డేట్ చేయడంలో సమస్య.');
    }

    /**
     * వార్తను డిలీట్ చేయడం
     */
    public function delete($id)
    {
        $newsModel = new NewsModel();
        $userId = session()->get('user_id');

        $news = $newsModel->where(['id' => $id, 'author_id' => $userId])->first();
        if ($news) {
            if (!empty($news['featured_image']) && file_exists(FCPATH . 'uploads/news/' . $news['featured_image'])) {
                unlink(FCPATH . 'uploads/news/' . $news['featured_image']);
            }
            $newsModel->delete($id);
            return redirect()->to('reporter/dashboard')->with('success', 'వార్త తొలగించబడింది.');
        }
        return redirect()->to('reporter/dashboard')->with('error', 'అనుమతి లేదు.');
    }

    /**
     * AJAX: Get Children
     */
    public function getChildren($parentId)
    {
        $categoryModel = new CategoryModel();
        $children = $categoryModel->where('parent_id', $parentId)
                                  ->where('IsActice', 1)
                                  ->orderBy('name', 'ASC')
                                  ->findAll();

        return $this->response->setJSON($children);
    }

    public function profile() {
        $userModel = new UserModel();
        $userId = session()->get('user_id');
        $reporter = $userModel->find($userId);

        if (!$reporter) return redirect()->to('login');

        $data = [
            'title'    => 'నా ప్రొఫైల్ - NToday',
            'reporter' => $reporter,
        ];
        return view('reporter/profile', $data);
    }
}