<!DOCTYPE html>
<html lang="te">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?> | NToday</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --sidebar-bg: #0f172a; --main-bg: #f8fafc; --accent: #3b82f6; }
        body { background-color: var(--main-bg); font-family: 'Inter', sans-serif; overflow-x: hidden; }

        /* Sidebar Styles */
        .sidebar { 
            width: 280px; height: 100vh; position: fixed; 
            background: var(--sidebar-bg); z-index: 1100;
            transition: transform 0.3s ease-in-out;
            left: 0; transform: translateX(-100%);
        }
        .sidebar.show { transform: translateX(0); }

        .main-content { margin-left: 0; padding: 15px; transition: margin 0.3s ease-in-out; }

        @media (min-width: 992px) {
            .sidebar { transform: translateX(0); }
            .main-content { margin-left: 280px; padding: 30px; }
            #sidebarOverlay { display: none !important; }
        }

        #sidebarOverlay {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050;
        }

        /* Nav & Submenu Styles */
        .nav-link { color: #94a3b8; padding: 12px 20px; border-radius: 12px; margin-bottom: 4px; display: flex; align-items: center; transition: 0.3s; }
        .nav-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .nav-link.active { background: var(--accent); color: white; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
        
        /* Dropdown Arrow animation */
        .nav-link[data-bs-toggle="collapse"]::after {
            display: inline-block; content: "\F282"; font-family: "bootstrap-icons";
            margin-left: auto; transition: transform 0.3s; font-size: 0.8rem;
        }
        .nav-link[aria-expanded="true"]::after { transform: rotate(180deg); }

        .submenu { list-style: none; padding-left: 1.5rem; margin-bottom: 10px; }
        .submenu .nav-link { font-size: 0.85rem; padding: 8px 15px; }

        .sidebar-label { font-size: 0.7rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 1.2px; margin: 20px 0 10px 20px; }
        
        .sticky-top-nav { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
    </style>
</head>
<body>

<div id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="sidebar d-flex flex-column p-3 shadow-lg" id="adminSidebar">
    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <h4 class="text-white fw-bold mb-0">N<span class="text-primary">Today</span> <small class="fs-6 opacity-50">Admin</small></h4>
        <button class="btn text-white d-lg-none" onclick="toggleSidebar()"><i class="bi bi-x-lg"></i></button>
    </div>
    
    <nav class="nav flex-column mb-auto scrollbar-hidden" id="adminNavbar" style="overflow-y: auto;">
        <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= ($title == 'Dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2 me-3"></i> Dashboard
        </a>

        <div class="sidebar-label">వార్తలు (News)</div>
        
        <a href="#newsMenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false">
            <i class="bi bi-newspaper me-3"></i> News Content
        </a>
        <div class="collapse" id="newsMenu" data-bs-parent="#adminNavbar">
            <ul class="submenu">
                <li><a href="<?= base_url('admin/news/add') ?>" class="nav-link"><i class="bi bi-plus-circle me-2"></i> Add News</a></li>
                <li><a href="<?= base_url('admin/news/manage') ?>" class="nav-link"><i class="bi bi-collection me-2"></i> Manage News</a></li>
            </ul>
        </div>

                <li class="nav-item">
            <a class="nav-link <?= (url_is('admin/banners*')) ? 'active' : '' ?>" href="<?= base_url('admin/banners') ?>">
                <i class="bi bi-image-fill me-2"></i>
                <span>Banner Ads Management</span>
            </a>
        </li>

        <a href="#approvalMenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false">
            <i class="bi bi-shield-check me-3"></i> Approvals
        </a>
        <div class="collapse" id="approvalMenu" data-bs-parent="#adminNavbar">
            <ul class="submenu">
                <li><a href="<?= base_url('admin/news/pending') ?>" class="nav-link d-flex justify-content-between">
                    <span>Pending News</span>
                    <span class="badge bg-danger rounded-pill">!</span>
                </a></li>
                <li><a href="<?= base_url('admin/news/approved') ?>" class="nav-link">Approved News</a></li>
            </ul>
        </div>

        <div class="sidebar-label">నిర్వహణ (Management)</div>

        <a href="#reporterMenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false">
            <i class="bi bi-mic me-3"></i> Reporters
        </a>
        <div class="collapse" id="reporterMenu" data-bs-parent="#adminNavbar">
            <ul class="submenu">
                <li><a href="<?= base_url('admin/reporters/pending') ?>" class="nav-link">New Requests</a></li>
                <li><a href="<?= base_url('admin/reporters/manage') ?>" class="nav-link">Active Reporters</a></li>
            </ul>
        </div>

        <a href="<?= base_url('admin/editors') ?>" class="nav-link">
            <i class="bi bi-person-badge me-3"></i> Editors Management
        </a>

        <a href="<?= base_url('admin/categories') ?>" class="nav-link">
            <i class="bi bi-tags me-3"></i> Category Setup
        </a>

        <hr class="text-secondary opacity-25">
        
        <a href="<?= base_url('/') ?>" target="_blank" class="nav-link bg-primary bg-opacity-10 text-primary justify-content-center">
            <i class="bi bi-box-arrow-up-right me-2"></i> View Portal
        </a>
    </nav>

    <div class="mt-4 border-top border-secondary pt-3">
        <a href="<?= base_url('/logout') ?>" class="btn btn-danger w-100 rounded-pill shadow-sm"><i class="bi bi-power me-2"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <nav class="navbar navbar-light bg-white rounded-4 shadow-sm mb-4 px-3 sticky-top sticky-top-nav">
        <div class="container-fluid">
            <button class="btn btn-light d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="ms-auto d-flex align-items-center">
                <div class="me-3 text-end d-none d-sm-block">
                    <p class="mb-0 small fw-bold"><?= session()->get('username') ?? 'Administrator' ?></p>
                    <p class="mb-0 text-muted" style="font-size: 0.7rem;">Super Admin</p>
                </div>
                <div class="dropdown">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=0D6EFD&color=fff" class="rounded-circle border border-2 border-white shadow-sm" width="40" height="40" alt="Avatar">
                </div>
            </div>
        </div>
    </nav>