<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthGuard; // Custom Auth Filter Import

class Filters extends BaseFilters
{
    /**
     * కాన్ఫిగర్ అలియాసెస్ (Filter Aliases)
     * ఇక్కడ మనం క్రియేట్ చేసిన AuthGuard కి ఒక షార్ట్ నేమ్ ఇస్తున్నాం.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'authGuard'     => AuthGuard::class, // NToday Authentication Guard
        'auth'          => \App\Filters\AuthGuard::class,
    ];

    /**
     * స్పెషల్ రిక్వైర్డ్ ఫిల్టర్స్
     * ఇవి సిస్టమ్ ద్వారా ఆటోమేటిక్‌గా గ్లోబల్ గా రన్ అవుతాయి.
     */
    public array $required = [
        'before' => [
            'forcehttps', // HTTPS ని ఫోర్స్ చేస్తుంది
            'pagecache',  // స్పీడ్ కోసం క్యాషింగ్
        ],
        'after' => [
            'pagecache',   
            'performance', // లోడింగ్ టైమ్ ని ట్రాక్ చేస్తుంది
            'toolbar',     // డెవలప్‌మెంట్ టూల్‌బార్
        ],
    ];

    /**
     * గ్లోబల్ ఫిల్టర్స్
     * ప్రతి రిక్వెస్ట్ కి ముందు లేదా తర్వాత రన్ అయ్యేవి.
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf', // ఫామ్ సబ్మిషన్స్ కి సెక్యూరిటీ కావాలంటే దీనిని ఎనేబుల్ చేయండి
        ],
        'after' => [
            // 'secureheaders',
        ],
    ];

    /**
     * పర్టికులర్ HTTP మెథడ్స్ కోసం
     */
    public array $methods = [];

    /**
     * URI ప్యాటర్న్స్ ఆధారంగా ప్రొటెక్షన్
     * ఇక్కడ మనం అడ్మిన్ మరియు రిపోర్టర్ ఏరియాని ఆటోమేటిక్ గా లాక్ చేస్తున్నాం.
     */
    public array $filters = [
        'authGuard' => [
            'before' => [
                'admin/*',     // Admin కి మాత్రమే అనుమతి
                'reporter/*',  // Reporter కి మాత్రమే అనుమతి
                'editor/*'     // Editor కి మాత్రమే అనుమతి
            ]
        ],
    ];
}