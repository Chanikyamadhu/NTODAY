<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    // 1. లాగిన్ పేజీ
    public function index()
    {
        return view('auth/login');
    }

    // 2. సైన్అప్ పేజీ
    public function signup()
    {
        return view('auth/signup');
    }

    // 3. రిపోర్టర్ రిజిస్ట్రేషన్
    public function store()
    {
        $session = session();
        $model = new UserModel();

        $file = $this->request->getFile('profile_pic');
        $profileName = 'default.png';
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $profileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profile_pics', $profileName);
        }

        $categories = $this->request->getPost('categories');
        $tags = $categories ? implode(',', $categories) : '';

        $data = [
            'username'     => $this->request->getPost('display_name'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'full_name'    => $this->request->getPost('full_name'),
            'email'        => $this->request->getPost('email'),
            'phone'        => $this->request->getPost('phone'),
            'gender'       => $this->request->getPost('gender'),
            'dob'          => $this->request->getPost('dob'),
            'role'         => 'reporter',
            'profile_pic'  => $profileName,
            'display_name' => $this->request->getPost('display_name'),
            'address'      => $this->request->getPost('address'),
            'area_coverage'=> $this->request->getPost('area_coverage'),
            'news_tags'    => $tags,
            'status'       => 0,
            'is_active'    => 0 
        ];

        if ($model->insert($data)) {
            return redirect()->to('/login')->with('success', 'రిజిస్ట్రేషన్ విజయవంతమైంది! అడ్మిన్ ఆమోదం కోసం వేచి చూడండి.');
        } else {
            return redirect()->back()->withInput()->with('error', 'రిజిస్ట్రేషన్ విఫలమైంది.');
        }
    }

    // 4. లాగిన్ ఆథెంటికేషన్
    public function auth()
    {
        $session = session();
        $model = new UserModel();
        
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $selectedRole = $this->request->getVar('role'); 
        
        $user = $model->where('email', $email)->first();

        if ($user) {
            if ($user['role'] !== $selectedRole) {
                return redirect()->back()->with('error', "క్షమించండి! మీరు ఎంచుకున్న రోల్ తప్పు.");
            }

            if ($user['status'] == 0) {
                return redirect()->back()->with('error', "మీ ఖాతా ఇంకా ఆక్టివేట్ కాలేదు.");
            }

            if (password_verify($password, $user['password'])) {
                $session->set([
                    'user_id'    => $user['id'],
                    'username'   => $user['username'],
                    'role'       => $user['role'],
                    'isLoggedIn' => TRUE
                ]);
                return redirect()->to("/{$user['role']}/dashboard");
            } else {
                return redirect()->back()->with('error', 'పాస్‌వర్డ్ తప్పు!');
            }
        } else {
            return redirect()->back()->with('error', 'ఇమెయిల్ రిజిస్టర్ అయి లేదు!');
        }
    }

    // 5. లాగౌట్
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out safely.');
    }

    // --- FORGOT PASSWORD METHODS START ---

    // 6. Forgot Password పేజీని చూపుతుంది
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    // 7. పాస్‌వర్డ్ రీసెట్ లింక్ ప్రాసెస్ చేస్తుంది
    public function processForgotPassword()
    {
        $email = $this->request->getVar('email');
        $model = new UserModel();
        $user = $model->where('email', $email)->first();

        if ($user) {
            // ఇక్కడ మీరు ఇమెయిల్ పంపే లాజిక్ రాసుకోవాలి
            // ప్రస్తుతానికి సక్సెస్ మెసేజ్ మాత్రమే చూపిస్తున్నాం
            return redirect()->back()->with('success', 'పాస్‌వర్డ్ రీసెట్ సూచనలు మీ ఇమెయిల్‌కు పంపబడ్డాయి.');
        }

        return redirect()->back()->with('error', 'ఈ ఇమెయిల్ అడ్రస్ మా వద్ద రిజిస్టర్ అయి లేదు.');
    }

    // --- FORGOT PASSWORD METHODS END ---
}