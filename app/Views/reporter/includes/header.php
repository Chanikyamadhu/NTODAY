<!DOCTYPE html>
<html lang="te">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'NToday Reporter Panel' ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Telugu:wght@400;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root { 
            --ntoday-red: #d32f2f; 
            --ntoday-dark: #0f172a; 
        }
        body { 
            background-color: #f8fafc; 
            font-family: 'Inter', 'Noto Sans Telugu', sans-serif; 
        }
        .reporter-nav {
            background-color: var(--ntoday-dark);
            padding: 10px 0;
        }
    </style>
</head>
<body>

<nav class="reporter-nav shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="<?= base_url('reporter/dashboard') ?>">
            <img src="<?= base_url('assets/images/Logo-R.png') ?>" alt="Logo" style="height: 40px; filter: brightness(0) invert(1);">
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white small d-none d-md-block">నమస్కారం, <strong><?= session()->get('reporter_name') ?></strong></span>
            <a href="<?= base_url('reporter/logout') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">Logout</a>
        </div>
    </div>
</nav>