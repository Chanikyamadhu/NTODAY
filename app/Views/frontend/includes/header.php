<!DOCTYPE html>
<html lang="te">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'NToday - తెలుగు వార్తలు' ?></title>
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Telugu:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --ntoday-red: #dc3545; 
            --accent-red: #dc3545; 
            --ntoday-dark: #0f172a; 
            --ntoday-yellow: #ffc107; 
            --bg-color: #f8fafc;
            --text-color: #1a1a1a;
            --header-bg: #ffffff;
            --border-color: #dee2e6;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            
            /* Enhanced Category Colors */
            --cat-state: #0d6efd; /* Blue for State */
            --cat-main: #6610f2;  /* Indigo for Main */
            --cat-live: #dc3545;  /* Red for Live */
            --cat-auth: #198754;  /* Green for Login/Join */
            --cat-radio: #fd7e14; /* Orange for Radio */
        }

        [data-theme="dark"] {
            --bg-color: #0b0f13;
            --text-color: #e9ecef;
            --header-bg: #121921;
            --border-color: #2d3741;
        }

        body { font-family: 'Noto Sans Telugu', sans-serif; background-color: var(--bg-color); color: var(--text-color); transition: 0.3s; }

        /* HEADER STYLING */
        .ntoday-header { background-color: var(--header-bg); padding: 10px 0; border-bottom: 3px solid var(--ntoday-red); }
        .logo-img { height: 50px; width: auto; transition: 0.3s; }
        
        .epaper-link {
            text-decoration: none; color: var(--ntoday-red); font-weight: 700; font-size: 0.9rem;
            border: 1px solid var(--ntoday-red); padding: 5px 12px; border-radius: 4px;
            position: relative; transition: 0.3s;
        }
        .epaper-link:hover { background: var(--ntoday-red); color: #fff; }
        .coming-soon-badge {
            position: absolute; top: -12px; right: -5px; font-size: 0.6rem;
            background: var(--ntoday-yellow); color: #000; padding: 1px 4px;
            border-radius: 3px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* ENHANCED SIDEBAR STYLE */
        .sidebar { 
            height: 100%; width: 0; position: fixed; z-index: 2000; top: 0; left: 0; 
            background: #111; overflow-x: hidden; transition: 0.4s; padding-top: 0; 
        }
        
        .sidebar-header { 
            padding: 25px; background: #000; border-bottom: 2px solid var(--ntoday-red); 
        }
        
        .sidebar a { 
            padding: 14px 25px; text-decoration: none; font-size: 15px; color: #fff; 
            display: flex; align-items: center; transition: 0.3s; 
            font-weight: 500; border-left: 5px solid transparent;
            margin-bottom: 1px;
        }

        /* Color Variations by Category */
        .sidebar a.nav-state { background: rgba(13, 110, 253, 0.15); border-left-color: var(--cat-state); }
        .sidebar a.nav-state:hover { background: var(--cat-state); }

        .sidebar a.nav-main { background: rgba(102, 16, 242, 0.15); border-left-color: var(--cat-main); }
        .sidebar a.nav-main:hover { background: var(--cat-main); }

        .sidebar a.nav-live { background: rgba(220, 53, 69, 0.2); border-left-color: var(--cat-live); font-weight: 700; }
        .sidebar a.nav-live:hover { background: var(--cat-live); }

        .sidebar a.nav-radio { background: rgba(253, 126, 20, 0.15); border-left-color: var(--cat-radio); }
        .sidebar a.nav-radio:hover { background: var(--cat-radio); }

        .sidebar a.nav-auth { background: rgba(25, 135, 84, 0.15); border-left-color: var(--cat-auth); }
        .sidebar a.nav-auth:hover { background: var(--cat-auth); }

        .sidebar a:hover { padding-left: 35px; color: #fff !important; }
        
        .sidebar .section-label { 
            padding: 18px 25px 8px; font-size: 11px; text-transform: uppercase; 
            color: #777; font-weight: 800; letter-spacing: 1.5px; background: #000;
        }

        /* TICKER STYLING */
        .breaking-news-ticker {
            display: flex; align-items: center; background: var(--header-bg);
            border: 1px solid var(--border-color); border-left: 4px solid var(--ntoday-red);
            border-radius: 8px; overflow: hidden; margin: 15px auto; height: 44px;
        }
        .breaking-label {
            background: var(--ntoday-red); color: #fff; padding: 0 18px;
            height: 100%; display: flex; align-items: center; font-weight: 700; font-size: 0.85rem;
        }
        .ticker-content { flex-grow: 1; padding-left: 15px; font-weight: 600; overflow: hidden; }
        .ticker-item { color: var(--text-color); text-decoration: none; margin-right: 40px; }

        .cursor-pointer { cursor: pointer; transition: 0.2s; }
        .cursor-pointer:hover { color: var(--ntoday-red); }
        .animate-flash { animation: flash 1.5s infinite; }
        .live-dot { height: 10px; width: 10px; background-color: #fff; border-radius: 50%; display: inline-block; margin-right: 8px; }
        @keyframes flash { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
    </style>
</head>
<body>

    <div id="mySidebar" class="sidebar shadow-lg">
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="<?= base_url('assets/images/Logo-R.png') ?>" style="height:35px; filter: brightness(0) invert(1);" alt="">
            </div>
            <a href="javascript:void(0)" class="closebtn text-white fs-2 p-0" onclick="closeNav()">&times;</a>
        </div>

        <a href="<?= base_url('/') ?>" style="background: #222;">
            <i class="fas fa-house-user me-3"></i> హోమ్ (Home)
        </a>

        <div class="section-label">Live Streaming</div>
        <a href="<?= base_url('live-tv') ?>" class="nav-live">
            <span class="live-dot animate-flash"></span> <i class="fas fa-tv me-3"></i> లైవ్ టీవీ (Live TV)
        </a>
        <a href="<?= base_url('live-radio') ?>" class="nav-radio">
            <i class="fas fa-radio me-3"></i> లైవ్ రేడియో (Live Radio)
        </a>
        
        <div class="section-label">ప్రాంతీయ వార్తలు (State)</div>
        <?php if(!empty($categories)): foreach($categories as $cat): 
            if($cat['type'] == 'state' && $cat['IsActice'] == 1): ?>
            <a href="<?= base_url('category/'.$cat['id']) ?>" class="nav-state">
                <i class="fas fa-map-location-dot me-3"></i> <?= esc($cat['name']) ?>
            </a>
        <?php endif; endforeach; endif; ?>

        <div class="section-label">ప్రధాన విభాగాలు (Main)</div>
        <?php if(!empty($categories)): foreach($categories as $cat): 
            if($cat['type'] == 'main' && $cat['IsActice'] == 1): ?>
            <a href="<?= base_url('category/'.$cat['id']) ?>" class="nav-main">
                <i class="fas fa-layer-group me-3"></i> <?= esc($cat['name']) ?>
            </a>
        <?php endif; endforeach; endif; ?>
        
        <div class="section-label">Account & Join Us</div>
        <a href="<?= base_url('signup') ?>" class="nav-auth">
            <i class="fas fa-user-plus me-3"></i> రిపోర్టర్ అవ్వండి (Join)
        </a>
        <a href="<?= base_url('login') ?>" class="nav-auth">
            <i class="fas fa-sign-in-alt me-3"></i> అధికారిక లాగిన్ (Login)
        </a>

        <div class="p-4 text-center">
            <small class="text-muted">© 2026 NToday News</small>
        </div>
    </div>

    <header class="sticky-top shadow-sm">
        <div class="ntoday-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-4 d-flex align-items-center">
                        <i class="fas fa-bars fa-lg me-3 cursor-pointer" onclick="openNav()"></i>
                        <i id="theme-icon" class="fas fa-moon fa-lg theme-toggle cursor-pointer" onclick="toggleTheme()"></i>
                    </div>
                    <div class="col-4 text-center">
                        <a href="<?= base_url('/') ?>">
                            <img src="<?= base_url('assets/images/Logo-R.png') ?>" alt="NToday Logo" class="logo-img">
                        </a>
                    </div>
                    <div class="col-4 text-end">
                        <a href="#" class="epaper-link">
                            <i class="bi bi-newspaper me-1"></i> E-Paper
                            <span class="coming-soon-badge">Soon</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container">
            <div class="breaking-news-ticker shadow-sm">
                <div class="breaking-label">
                    <i class="bi bi-lightning-fill me-1 animate-flash"></i> బ్రేకింగ్
                </div>
                <div class="ticker-content">
                    <marquee onmouseover="this.stop();" onmouseout="this.start();" scrollamount="6">
                        <?php if(!empty($breaking_news)): foreach($breaking_news as $news): ?>
                            <a href="<?= base_url('news/'.$news['slug']) ?>" class="ticker-item text-decoration-none">
                                <span class="text-danger fw-bold mx-2">●</span> 
                                <?= esc($news['title']) ?> 
                                <span class="mx-3 text-muted">|</span>
                            </a>
                        <?php endforeach; else: ?>
                            <span class="ticker-item">తాజా బ్రేకింగ్ వార్తలు ఇక్కడ అప్‌డేట్ చేయబడతాయి...</span>
                        <?php endif; ?>
                    </marquee>
                </div>
            </div>
        </div>
    </header>

    

    <script>
        function openNav() { 
            document.getElementById("mySidebar").style.width = window.innerWidth < 576 ? "100%" : "300px"; 
        }
        function closeNav() { 
            document.getElementById("mySidebar").style.width = "0"; 
        }

        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            if (html.getAttribute('data-theme') === 'dark') {
                html.removeAttribute('data-theme');
                icon.classList.replace('fa-sun', 'fa-moon');
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                icon.classList.replace('fa-moon', 'fa-sun');
                localStorage.setItem('theme', 'dark');
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            const icon = document.getElementById('theme-icon');
            if (savedTheme === 'dark') {
                icon.classList.replace('fa-moon', 'fa-sun');
            }
        });
    </script>
</body>
</html>