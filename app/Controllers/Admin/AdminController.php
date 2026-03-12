<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\NewsModel;
use App\Models\BannersModel;

class AdminController extends BaseController
{
    protected $helpers = ['url', 'form'];

    // 1. అడ్మిన్ డాష్‌బోర్డ్ - గణాంకాలు మరియు అనలిటిక్స్
    public function index()
    {
        $newsModel = new NewsModel();
        $userModel = new UserModel();
        
        // గణాంకాలు (Stats)
        $data = [
            'total_news_count'             => $newsModel->countAll(),
            'total_reporters'        => $userModel->where('role', 'reporter')->countAllResults(),
            'active_reporters_count' => $userModel->where(['role' => 'reporter', 'status' => 1])->countAllResults(),
            'pending_news_count'     => $newsModel->where('status', 'pending')->countAllResults(),
            'pending_news'           => $newsModel->select('news.*, users.display_name as reporter_name')
                                                 ->join('users', 'users.id = news.author_id', 'left')
                                                 ->where('news.status', 'pending')
                                                 ->orderBy('news.created_at', 'DESC')
                                                 ->findAll(5),
            'pending_reporters'      => $userModel->where(['role' => 'reporter', 'status' => 'pending'])->findAll(),
            'pending_reporters_count' => $userModel->where(['role' => 'reporter', 'status' => 'pending'])->countAllResults(),
            'pending_editors_count'   => $userModel->where(['role' => 'editor', 'status' => 'pending'])->countAllResults(),
            'total_views'            => $newsModel->selectSum('view_count')->first()['view_count'] ?? 0,
            'todays_news_count'      => $newsModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults(),
            'title'                  => 'Admin Dashboard - NToday'
        ];

        // --- Enhancements ---
        
        // వీక్లీ స్టార్ రిపోర్టర్
        $data['star_reporter'] = $userModel->select('users.*, COUNT(news.id) as news_count')
                                           ->join('news', 'news.author_id = users.id')
                                           ->where('news.created_at >=', date('Y-m-d', strtotime('-7 days')))
                                           ->groupBy('users.id')
                                           ->orderBy('news_count', 'DESC')
                                           ->first();

        // రిపోర్టర్ పర్ఫార్మెన్స్ (Top 5)
        $data['reporter_performance'] = $userModel->select('display_name, COUNT(news.id) as news_count')
                                                 ->join('news', 'news.author_id = users.id')
                                                 ->groupBy('users.id')
                                                 ->orderBy('news_count', 'DESC')
                                                 ->limit(5)
                                                 ->findAll();

        // వైరల్ న్యూస్ (Weekly)
        $data['weekly_top_news'] = $newsModel->select('news.*, categories.name as category_name, users.display_name as reporter_name')
                                             ->join('categories', 'categories.id = news.category_id', 'left')
                                             ->join('users', 'users.id = news.author_id', 'left')
                                             ->where('news.created_at >=', date('Y-m-d', strtotime('-7 days')))
                                             ->orderBy('view_count', 'DESC')
                                             ->findAll(5);

        return view('admin/dashboard', $data);
    }

    // --- 2. BANNER MANAGEMENT ---

    public function manageBanners()
    {
        $model = new BannersModel();
        $data['banners'] = $model->orderBy('id', 'DESC')->findAll();
        $data['title']   = 'Manage Banners - NToday';
        
        return view('admin/banners/index', $data);
    }

    public function storeBanner()
    {
        $model = new BannersModel();
        $file = $this->request->getFile('banner_image');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/banners', $newName);

            $saveData = [
                'title'      => $this->request->getPost('title'),
                'summary'    => $this->request->getPost('summary'),
                'image_path' => $newName,
                'link_url'   => $this->request->getPost('link_url'),
                'position'   => $this->request->getPost('position') ?? 'top_banner',
                'status'     => 1
            ];

            if ($model->insert($saveData)) {
                return redirect()->back()->with('success', 'బానర్ విజయవంతంగా అప్‌లోడ్ చేయబడింది!');
            }
        }
        return redirect()->back()->with('error', 'చెల్లుబాటు అయ్యే ఇమేజ్ ఫైల్‌ను ఎంచుకోండి.');
    }

    public function deleteBanner($id)
    {
        $model = new BannersModel();
        $banner = $model->find($id);

        if ($banner) {
            $filePath = FCPATH . 'uploads/banners/' . $banner['image_path'];
            if (file_exists($filePath) && is_file($filePath)) {
                unlink($filePath);
            }
            $model->delete($id);
            return redirect()->back()->with('success', 'బ్యానర్ విజయవంతంగా తొలగించబడింది!');
        }
        return redirect()->back()->with('error', 'బ్యానర్ లభించలేదు!');
    }

    // --- 3. REPORTER MANAGEMENT ---

    public function pendingReporters()
    {
        $userModel = new UserModel();
        $reporters = $userModel->where(['role' => 'reporter', 'status' => 0])
                               ->orderBy('created_at', 'DESC')
                               ->findAll();
        
        $data = ['reporters' => $reporters, 'title' => 'Pending Reporter Approvals'];
        return view('admin/reporters/pending', $data);
    }

    public function manageReporters()
    {
        $userModel = new UserModel();
        $newsModel = new NewsModel();

        // Status 1 (Active) మరియు 2 (Inactive) ఉన్నవారిని మాత్రమే తీసుకోవడం
        $reporters = $userModel->where('role', 'reporter')
                            ->whereIn('status', [1, 2]) // ఇక్కడ మార్పు చేయబడింది
                            ->orderBy('display_name', 'ASC')
                            ->findAll();

        foreach ($reporters as &$reporter) {
            $reporter['total_news'] = $newsModel->where('author_id', $reporter['id'])->countAllResults();
        }

        $data = ['reporters' => $reporters, 'title' => 'Manage Reporters - NToday'];
        return view('admin/reporters/index', $data);
    }

    public function approveReporter($id)
    {
        $userModel = new UserModel();
        if ($userModel->update($id, ['status' => 'active'])) { // status ను 'active' గా అప్‌డేట్ చేస్తున్నాను
            return redirect()->to('admin/dashboard')->with('success', 'రిపోర్టర్ అప్రూవ్ చేయబడ్డారు!');
        }
        return redirect()->back()->with('error', 'ప్రక్రియ విఫలమైంది.');
    }

    public function rejectReporter($id)
    {
        $userModel = new UserModel();
        if ($userModel->delete($id)) {
            return redirect()->to('admin/dashboard')->with('success', 'రిపోర్టర్ తొలగించబడ్డారు.');
        }
        return redirect()->back()->with('error', 'తొలగించడం సాధ్యపడలేదు.');
    }

    // app/Controllers/Admin/AdminController.php

    public function editReporter($id)
    {
        $userModel = new \App\Models\UserModel();
        $data['reporter'] = $userModel->find($id);

        if (!$data['reporter']) {
            return redirect()->to('admin/reporters')->with('error', 'రిపోర్టర్ దొరకలేదు.');
        }

        $data['title'] = 'Edit Reporter';
        return view('admin/reporters/edit', $data);
    }

    public function updateReporter($id)
    {
        $userModel = new \App\Models\UserModel();

        // 1. రిపోర్టర్ ఉనికిని తనిఖీ చేయండి
        $reporter = $userModel->find($id);
        if (!$reporter) {
            return redirect()->to('admin/reporters/manage')->with('error', 'రిపోర్టర్ దొరకలేదు.');
        }

        // 2. సాధారణ డేటా సేకరణ
        $updateData = [
            'display_name'  => $this->request->getPost('display_name'),
            'full_name'     => $this->request->getPost('full_name'),
            'email'         => $this->request->getPost('email'),
            'phone'         => $this->request->getPost('phone'),
            'gender'        => $this->request->getPost('gender'),
            'address'       => $this->request->getPost('address'),
            'bio'           => $this->request->getPost('bio'),
            'area_coverage' => $this->request->getPost('area_coverage'),
            'pincode'       => $this->request->getPost('pincode'),
            'news_tags'     => $this->request->getPost('news_tags'),
            'status'        => $this->request->getPost('status'),
        ];

        // --- 3. Profile Picture Crop Logic (Base64) ---
        $croppedImage = $this->request->getPost('cropped_profile_pic');

        if (!empty($croppedImage)) {
            // పాత ఫోటో ఉంటే దాన్ని సర్వర్ నుండి డిలీట్ చేయడం
            if (!empty($reporter['profile_pic']) && file_exists(FCPATH . 'uploads/profile/' . $reporter['profile_pic'])) {
                unlink(FCPATH . 'uploads/profile/' . $reporter['profile_pic']);
            }

            // Base64 డేటాను ప్రాసెస్ చేయడం
            // డేటా ఫార్మాట్: data:image/jpeg;base64,/9j/4AAQSkZJRg...
            list($type, $data) = explode(';', $croppedImage);
            list(, $data)      = explode(',', $data);
            $imageData = base64_decode($data);

            // కొత్త ఫైల్ పేరు క్రియేట్ చేయడం
            $newName = 'profile_' . time() . '_' . rand(1111, 9999) . '.jpg';
            $uploadPath = FCPATH . 'uploads/profile/' . $newName;

            // ఫోల్డర్ లేకపోతే క్రియేట్ చేయడం
            if (!is_dir(FCPATH . 'uploads/profile/')) {
                mkdir(FCPATH . 'uploads/profile/', 0777, true);
            }

            // ఫైల్ ను సేవ్ చేయడం
            if (file_put_contents($uploadPath, $imageData)) {
                $updateData['profile_pic'] = $newName;
            }
        } 
        // ఒకవేళ క్రాప్ చేయకుండా డైరెక్ట్ ఫైల్ పంపితే (Backup logic)
        else {
            $file = $this->request->getFile('profile_pic');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if (!empty($reporter['profile_pic']) && file_exists(FCPATH . 'uploads/profile/' . $reporter['profile_pic'])) {
                    unlink(FCPATH . 'uploads/profile/' . $reporter['profile_pic']);
                }
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/profile/', $newName);
                $updateData['profile_pic'] = $newName;
            }
        }

        // 4. DOB & Password Handling
        $dob = $this->request->getPost('dob');
        $updateData['dob'] = (!empty($dob)) ? $dob : null;

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // 5. Database Update
        try {
            if ($userModel->update($id, $updateData)) {
                return redirect()->to('admin/reporters/manage')->with('success', 'రిపోర్టర్ ప్రొఫైల్ విజయవంతంగా అప్‌డేట్ చేయబడింది!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'డేటాబేస్ లోపం: ' . $e->getMessage());
        }

        return redirect()->back()->withInput()->with('error', 'వివరాలను అప్‌డేట్ చేయడం సాధ్యపడలేదు.');
    }
    public function reporterStats($id)
    {
        $newsModel = new \App\Models\NewsModel();
        $userModel = new \App\Models\UserModel();

        $reporter = $userModel->find($id);
        if (!$reporter) {
            return redirect()->back()->with('error', 'రిపోర్టర్ దొరకలేదు.');
        }

        // ఆ రిపోర్టర్ రాసిన వార్తల గణాంకాలు
        $data = [
            'title'          => $reporter['display_name'] . ' - గణాంకాలు',
            'reporter'       => $reporter,
            'total_news'     => $newsModel->where('author_id', $id)->countAllResults(),
            'published_news' => $newsModel->where(['author_id' => $id, 'status' => 1])->countAllResults(),
            'total_views'    => $newsModel->where('author_id', $id)->selectSum('view_count')->get()->getRow()->view_count ?? 0,
            // గత 30 రోజుల వార్తలు
            'recent_news'    => $newsModel->where('author_id', $id)->orderBy('created_at', 'DESC')->limit(10)->findAll()
        ];

        return view('admin/reporters/stats', $data);
    }

    public function deactivateReporter($id)
    {
        $userModel = new \App\Models\UserModel();

        // రిపోర్టర్ ఉన్నారో లేదో చెక్ చేయండి
        $reporter = $userModel->find($id);
        if (!$reporter) {
            return redirect()->back()->with('error', 'రిపోర్టర్ దొరకలేదు.');
        }

        // స్టేటస్‌ను 2 (Inactive) గా మార్చండి
        $updateData = [
            'status' => 2 // 2 అంటే Inactive అని మనం గతంలో సెట్ చేసుకున్నాం
        ];

        if ($userModel->update($id, $updateData)) {
            return redirect()->to('admin/reporters/manage')->with('success', 'రిపోర్టర్ విజయవంతంగా డీయాక్టివేట్ చేయబడ్డారు.');
        } else {
            return redirect()->back()->with('error', 'డీయాక్టివేట్ చేయడంలో సమస్య ఏర్పడింది.');
        }
    }

    public function activateReporter($id)
    {
        $userModel = new \App\Models\UserModel();
        if ($userModel->update($id, ['status' => 1])) {
            return redirect()->to('admin/reporters/manage')->with('success', 'రిపోర్టర్ ఇప్పుడు యాక్టివ్‌లో ఉన్నారు.');
        }
        return redirect()->back()->with('error', 'యాక్టివేట్ చేయడం కుదరలేదు.');
    }

    // --- 4. EDITOR MANAGEMENT ---

    public function manageEditors()
    {
        $userModel = new UserModel();
        $data = [
            'editors' => $userModel->where('role', 'editor')->orderBy('display_name', 'ASC')->findAll(),
            'title'   => 'Manage Editors - NToday'
        ];
        return view('admin/editors/index', $data);
    }

    public function addEditor() 
    {
        $data = ['title' => 'Add New Editor - NToday'];
        return view('admin/editors/add', $data);
    }

    public function storeEditor() 
    {
        $userModel = new UserModel();
        $data = [
            'display_name' => $this->request->getPost('display_name'),
            'email'        => $this->request->getPost('email'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => 'editor',
            'status'       => 'active',
            'created_at'   => date('Y-m-d H:i:s')
        ];

        if ($userModel->insert($data)) {
            return redirect()->to('admin/editors/manage')->with('success', 'ఎడిటర్ చేర్చబడ్డారు!');
        }
        return redirect()->back()->withInput()->with('error', 'రిజిస్ట్రేషన్ విఫలమైంది.');
    }
}