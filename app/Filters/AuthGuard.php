<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. అసలు యూజర్ లాగిన్ అవ్వకపోతే లాగిన్ పేజీకి పంపడం
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'ముందుగా లాగిన్ అవ్వండి.');
        }

        // 2. రోల్ బేస్డ్ చెక్ (Arguments ద్వారా)
        // ఉదాహరణకు రూట్స్ లో 'auth:admin' అని ఇస్తే, అది ఇక్కడ అడ్మిన్ కాదా అని చెక్ చేస్తుంది
        if (!empty($arguments)) {
            $userRole = $session->get('role'); // మీ సెషన్‌లో 'role' (admin/reporter) ఉండాలి

            if (!in_array($userRole, $arguments)) {
                // రోల్ మ్యాచ్ అవ్వకపోతే అనధికారిక ప్రవేశం (Unauthorized Access)
                return redirect()->to('/login')->with('error', 'ఈ పేజీని చూడటానికి మీకు అనుమతి లేదు.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}