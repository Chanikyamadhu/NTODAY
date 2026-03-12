<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NewsModel;
use App\Models\CategoryModel;

class News extends BaseController
{
    // కంట్రోలర్ లోడ్ అయినప్పుడే హెల్పర్స్ లోడ్ అవ్వడానికి
    protected $helpers = ['form', 'url'];

    public function index()
{
    $newsModel = new NewsModel();
    $catModel  = new CategoryModel();

    // వ్యూ నుండి వచ్చే ఫిల్టర్ డేటాను తీసుకోవడం
    $searchTerm = $this->request->getGet('search');
    $category   = $this->request->getGet('category');
    $status     = $this->request->getGet('status');

    // Query Builder ప్రారంభం
    // గమనిక: మీ users టేబుల్‌లో display_name కాలమ్ లేకపోతే ఎర్రర్ వస్తుంది, అది ఉందో లేదో చూసుకోండి.
    $builder = $newsModel->select('news.*, categories.name as category_name, users.display_name as full_name, users.role')
                         ->join('categories', 'categories.id = news.category_id', 'left')
                         ->join('users', 'users.id = news.author_id', 'left');

    // సెర్చ్ ఫిల్టర్ అమలు
    if (!empty($searchTerm)) {
        $builder->like('news.title', $searchTerm);
    }

    // కేటగిరీ ఫిల్టర్ అమలు
    if (!empty($category)) {
        $builder->where('news.category_id', $category);
    }

    // స్టేటస్ ఫిల్టర్ అమలు - ఇక్కడ మార్పు చేసాము (Resolving the 0/1 issue)
    if ($status !== null && strlen($status) > 0) {
        $builder->where('news.status', (int)$status);
    }

    $data = [
        'news_list'       => $builder->orderBy('news.created_at', 'DESC')->paginate(10),
        'pager'           => $newsModel->pager,
        'categories'      => $catModel->where('IsActice', 1)->whereIn('type', ['main', 'state'])
                                  ->findAll(), // ఫిల్టర్ డ్రాప్‌డౌన్ కోసం
        'search_term'     => $searchTerm, // సెర్చ్ బాక్సులో పాత వాల్యూ ఉండటానికి
        'selected_cat'    => $category,   // డ్రాప్‌డౌన్ లో సెలెక్ట్ అయ్యి ఉండటానికి
        'selected_status' => $status,     // డ్రాప్‌డౌన్ లో సెలెక్ట్ అయ్యి ఉండటానికి
        'title'           => 'Manage News - NToday Admin'
    ];

    return view('admin/news/index', $data);
}


    public function add()
    {
        $catModel = new \App\Models\CategoryModel();
        
        $data = [
            'categories' => $catModel->where('IsActice', 1)->findAll(),
            'title'      => 'Add New News'
        ];

        return view('admin/news/add', $data);
    }

    /**
 * తెలుగు అక్షరాలను ఇంగ్లీష్ ఉచ్చారణ (Sound) లోకి మార్చే ఫంక్షన్
 */
private function transliterateTelugu($text) {
    // అన్ని తెలుగు అక్షరాల ఉచ్చారణల టేబుల్
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

    // 1. అక్షరాలను రీప్లేస్ చేయడం
    $text = str_replace(array_keys($table), array_values($table), $text);
    
    // 2. తెలుగు వత్తులు (్) మరియు ఇతర యూనికోడ్ క్యారెక్టర్లను క్లీన్ చేయడం
    // కేవలం a-z, 0-9 మరియు స్పేస్ లను మాత్రమే ఉంచి మిగిలినవి తీసేస్తుంది
    $text = preg_replace('/[^\x20\x30-\x39\x41-\x5a\x61-\x7a]/u', '', $text);
    
    return $text;
}

public function store()
{
    $newsModel = new \App\Models\NewsModel();

    // 1. వ్యాలిడేషన్ రూల్స్
    $rules = [
        'title'       => 'required|min_length[10]',
        'content'     => 'required',
        'category_id' => 'required|numeric'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('error', 'దయచేసి వ్యాలిడేషన్ ఎర్రర్స్ సరిచూసుకోండి: ' . implode(', ', $this->validator->getErrors()));
    }

    // 2. ఇమేజ్ హ్యాండ్లింగ్ (Base64 to File)
    $croppedImage = $this->request->getPost('cropped_image');
    $imageName = null; 

    if (!empty($croppedImage) && strpos($croppedImage, 'data:image') !== false) {
        try {
            $image_parts = explode(";base64,", $croppedImage);
            $image_base64 = base64_decode($image_parts[1]);
            
            // Unique name for the image
            $imageName = time() . '_ntoday_news.jpg';
            $uploadPath = FCPATH . 'uploads/news/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            file_put_contents($uploadPath . $imageName, $image_base64);
        } catch (\Exception $e) {
            log_message('error', 'Image Upload Error: ' . $e->getMessage());
        }
    }

    // 3. ఆటోమేటిక్ సమ్మరీ మరియు స్లగ్ ట్రాన్స్‌లిటరేషన్
    $title = $this->request->getPost('title');
    $rawContent = $this->request->getPost('content');
    $summary = $this->request->getPost('summary') ?: substr(strip_tags($rawContent), 0, 250);

    // తెలుగు టైటిల్‌ను ఇంగ్లీష్ స్లగ్ కోసం మార్చడం
    $englishSoundTitle = $this->transliterateTelugu($title);
    $autoSlug = url_title($englishSoundTitle, '-', true) . '-' . bin2hex(random_bytes(3));

    // 4. డేటా ప్రిపరేషన్
    $data = [
        'title'            => $title,
        'slug'             => $autoSlug, 
        'content'          => $rawContent,
        'summary'          => $summary,
        'category_id'      => $this->request->getPost('category_id'),
        'sub_category_id'  => $this->request->getPost('sub_category_id') ?: null,
        'state_id'         => $this->request->getPost('category_id') ?: null,
        'district_id'      => $this->request->getPost('district_id') ?: null,
        'mandal_id'        => $this->request->getPost('mandal_id') ?: null,
        'village_id'       => $this->request->getPost('village_id') ?: null,
        'author_id'        => session()->get('user_id') ?: 1,
        'featured_image'   => $imageName,
        'meta_description' => $this->request->getPost('meta_description') ?: substr(strip_tags($summary), 0, 160),
        'meta_keywords'    => $this->request->getPost('meta_keywords'),
        'is_breaking_news' => $this->request->getPost('is_breaking_news') ? 1 : 0,
        'status'           => $this->request->getPost('status') ?? 1,
        'published_at'     => ($this->request->getPost('status') == 1) ? date('Y-m-d H:i:s') : null,
    ];

    // 5. డేటాబేస్ ఇన్సర్ట్
    if ($newsModel->insert($data)) {
        return redirect()->to('admin/news')->with('success', 'వార్త విజయవంతంగా ప్రచురించబడింది!');
    } else {
        $dbErrors = $newsModel->errors();
        return redirect()->back()->withInput()->with('error', 'డేటాబేస్ ఎర్రర్: ' . implode(', ', $dbErrors));
    }
}

    public function edit($id = null)
{
    if (!$id) {
        return redirect()->to('admin/news/manage')->with('error', 'వార్త ఐడి లభించలేదు.');
    }

    $newsModel = new \App\Models\NewsModel();
    $data['news'] = $newsModel->find($id);

    if (!$data['news']) {
        return redirect()->to('admin/news/manage')->with('error', 'వార్త దొరకలేదు.');
    }

    $categoryModel = new \App\Models\CategoryModel();
    // గమనిక: మీ టేబుల్ లో కాలమ్ పేరు 'is_active' అయితే ఇక్కడ అది వాడాలి.
    $data['categories'] = $categoryModel->where('IsActice', 1)->findAll(); 
    $data['title'] = "Edit News";

    return view('admin/news/edit', $data);
}

    public function update($id)
{
    $newsModel = new \App\Models\NewsModel();
    $oldNews = $newsModel->find($id);

    if (!$oldNews) {
        return redirect()->to('admin/news/manage')->with('error', 'వార్త దొరకలేదు.');
    }

    // 1. ఇమేజ్ హ్యాండ్లింగ్
    $imageName = $oldNews['featured_image']; 
    $croppedImage = $this->request->getPost('cropped_image');
    $uploadPath = FCPATH . 'uploads/news/';

    // డైరెక్టరీ లేకపోతే క్రియేట్ చేయడం
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    if (!empty($croppedImage) && strpos($croppedImage, 'data:image') !== false) {
        if ($oldNews['featured_image'] && file_exists($uploadPath . $oldNews['featured_image'])) {
            unlink($uploadPath . $oldNews['featured_image']);
        }
        $image_parts = explode(";base64,", $croppedImage);
        $image_base64 = base64_decode($image_parts[1]);
        $imageName = time() . '_updated_news.jpg';
        file_put_contents($uploadPath . $imageName, $image_base64);
    } else {
        $file = $this->request->getFile('featured_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($oldNews['featured_image'] && file_exists($uploadPath . $oldNews['featured_image'])) {
                unlink($uploadPath . $oldNews['featured_image']);
            }
            $imageName = $file->getRandomName();
            $file->move($uploadPath, $imageName);
        }
    }

    // 2. కంటెంట్ ప్రాసెసింగ్
    $rawContent = $this->request->getPost('content');
    $summary = $this->request->getPost('summary') ?: substr(strip_tags($rawContent), 0, 250);

    // 3. డేటా ప్రిపరేషన్ (వ్యూ లోని నేమ్స్ కి అనుగుణంగా మార్చాను)
    $data = [
        'title'            => $this->request->getPost('title'),
        'content'          => $rawContent,
        'summary'          => $summary,
        'category_id'      => $this->request->getPost('category_id'),
        'sub_category_id'  => $this->request->getPost('sub_category_id') ?: null,
        'district_id'      => $this->request->getPost('district_id') ?: null,
        'mandal_id'        => $this->request->getPost('mandal_id') ?: null,
        'village_id'       => $this->request->getPost('village_id') ?: null, // ఇక్కడ సరిచేశాను
        'featured_image'   => $imageName,
        'is_breaking_news'    => $this->request->getPost('is_breaking_news'),
        'meta_keywords'    => $this->request->getPost('meta_keywords'),
        'meta_description' => $this->request->getPost('meta_description') ?: substr(strip_tags($summary), 0, 160),
        'status'           => $this->request->getPost('status'),
        'updated_at'       => date('Y-m-d H:i:s')
    ];

    // వ్యాలిడేషన్ ఎర్రర్స్ చూడటానికి
    try {
        if ($newsModel->update($id, $data)) {
            return redirect()->to('admin/news/manage')->with('success', 'వార్త విజయవంతంగా సవరించబడింది.');
        } else {
            return redirect()->back()->withInput()->with('error', 'డేటాబేస్ అప్‌డేట్ విఫలమైంది.');
        }
    } catch (\Exception $e) {
        // అసలు ఎర్రర్ ఏంటో ఇక్కడ కనిపిస్తుంది
        return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
    }
}


    public function pending()
    {
        $newsModel = new \App\Models\NewsModel();
        $data['news'] = $newsModel->select('news.*, categories.name as category_name, users.display_name as reporter_name')
                                ->join('categories', 'categories.id = news.category_id', 'left')
                                ->join('users', 'users.id = news.author_id', 'left')
                                ->where('news.status', 'pending')
                                ->orderBy('news.created_at', 'DESC')
                                ->findAll();
                                
        return view('admin/news/pending', $data);
    }

    // 1. ఆమోదించే లాజిక్ (Approve)
    public function approve($id)
    {
        $newsModel = new NewsModel();
        
        // 1. మొదట వార్త ఉందో లేదో చెక్ చేయండి
        $news = $newsModel->find($id);
        if (!$news) {
            return redirect()->to('admin/news/pending')->with('error', 'వార్త దొరకలేదు.');
        }

        // 2. డేటా అప్‌డేట్ (Status ని 1 గా మార్చాలి, 'published' అని కాదు)
        $data = [
            'status'           => 1, // 1 అంటే Published, 0 అంటే Pending
            'published_at'     => date('Y-m-d H:i:s'),
            'rejected_reason'  => null 
        ];

        // 3. ఒకవేళ మీ మోడల్ లో వ్యాలిడేషన్ ఉంటే, దాన్ని తాత్కాలికంగా స్కిప్ చేయడం మంచిది
        if ($newsModel->skipValidation(true)->update($id, $data)) {
            return redirect()->to('admin/news/pending')->with('success', 'వార్త విజయవంతంగా ప్రచురించబడింది.');
        }

        // ఒకవేళ ఫెయిల్ అయితే ఎర్రర్ లాగ్ చూడండి
        return redirect()->back()->with('error', 'సాంకేతిక కారణాల వల్ల ఆమోదించడం సాధ్యపడలేదు.');
    }

    // 2. తిరస్కరించే లాజిక్ (Reject)
    public function reject($id)
    {
        $newsModel = new NewsModel();
        
        // ఫామ్ నుండి వచ్చే రీజన్ తీసుకోవడం
        $reason = $this->request->getPost('reject_reason');

        if (empty($reason)) {
            return redirect()->back()->with('error', 'దయచేసి తిరస్కరించడానికి గల కారణాన్ని తెలపండి.');
        }

        $data = [
            'status'          => 'rejected',
            'rejected_reason' => $reason
        ];

        if ($newsModel->update($id, $data)) {
            return redirect()->to('admin/news/pending')->with('success', 'వార్త తిరస్కరించబడింది (Rejected).');
        }

        return redirect()->back()->with('error', 'ప్రక్రియ విఫలమైంది.');
    }

    public function approved()
{
    $newsModel = new \App\Models\NewsModel();

    // క్వెరీ బిల్డర్ - కేవలం రిపోర్టర్ల ఆమోదించబడిన వార్తలు మాత్రమే
    $builder = $newsModel->select('news.*, categories.name as category_name, users.display_name as reporter_name')
                         ->join('categories', 'categories.id = news.category_id', 'left')
                         ->join('users', 'users.id = news.author_id', 'left')
                         ->where('users.role', 'reporter') // స్ట్రిక్ట్ గా రిపోర్టర్ రోల్
                         ->where('news.status', 1)        // ఆమోదించబడిన స్థితి (Bit 1 or 'published')
                         ->orderBy('news.published_at', 'DESC');

    $data = [
        'news'  => $builder->paginate(15),
        'pager' => $newsModel->pager,
        'title' => 'Approved News - NToday'
    ];

    return view('admin/news/approved', $data);
}

public function uploadImage()
{
    $file = $this->request->getFile('upload'); // CKEditor 'upload' అనే పేరుతో ఫైల్ పంపిస్తుంది

    if ($file && $file->isValid() && !$file->hasMoved()) {
        // ఇమేజ్ సేవ్ చేయాల్సిన ఫోల్డర్: public/uploads/news_content/
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/news_content', $newName);

        // CKEditor ఆశించే JSON రెస్పాన్స్
        return $this->response->setJSON([
            'uploaded' => true,
            'url'      => base_url('uploads/news_content/' . $newName)
        ]);
    }

    return $this->response->setJSON([
        'uploaded' => false,
        'error'    => ['message' => 'ఇమేజ్ అప్‌లోడ్ చేయడంలో విఫలమైంది.']
    ]);
}
}
