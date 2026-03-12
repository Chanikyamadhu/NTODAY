<?php
// --- SQL Dump నుండి సేకరించిన క్లీన్ డేటా (Dummy Data for View) ---

$breaking_news = [
    ['title' => 'CM Revanth Reddy at Secunderabad Bonalu - నిన్న మొన్నటి వరకు సందడి చేసిన బోనాలు'],
    ['title' => 'Jio Record: జియో సరికొత్త రికార్డు.. టెలికాం రంగంలో సంచలనం'],
    ['title' => 'Indian Railways: రైల్వే ప్రయాణికులకు శుభవార్త, కొత్త రైళ్ల ప్రకటన']
];

$latest_news = [
    [
        'title' => 'Chandrababu and YS Jagan: ఏపీ రాజకీయాల్లో మారుతున్న సమీకరణాలు',
        'slug' => 'chandrababu-ys-jagan-politics',
        'featured_image' => 'assets/uploads/2024/07/chandrababu-ys-jagan.webp',
        'content' => 'ఆంధ్రప్రదేశ్ రాజకీయాల్లో కీలక మార్పులు చోటుచేసుకుంటున్నాయి. చంద్రబాబు మరియు జగన్ మధ్య మాటల యుద్ధం ముదిరింది...',
        'category' => 'Politics',
        'created_at' => '2025-03-29 08:47:11'
    ],
    [
        'title' => 'Nirmala Sitharaman: బడ్జెట్ 2025 పై నిర్మలమ్మ కీలక వ్యాఖ్యలు',
        'slug' => 'nirmala-sitharaman-budget-speech',
        'featured_image' => 'assets/uploads/2024/07/nirmala-sitharaman.webp',
        'content' => 'దేశ ఆర్థిక వ్యవస్థ బలోపేతం దిశగా అడుగులు వేస్తోంది. సామాన్యులకు ఊరట కలిగించేలా కొత్త నిర్ణయాలు ఉంటాయని కేంద్ర మంత్రి తెలిపారు...',
        'category' => 'National',
        'created_at' => '2025-03-29 08:52:10'
    ],
    [
        'title' => 'Smriti Mandhana: క్రికెట్ మైదానంలో స్మృతి మంధన మెరుపులు',
        'slug' => 'smriti-mandhana-cricket-updates',
        'featured_image' => 'assets/uploads/2024/07/smriti-mandhana-6.webp',
        'content' => 'మహిళా క్రికెట్‌లో భారత స్టార్ బ్యాటర్ స్మృతి మంధన మరో సెంచరీతో అదరగొట్టింది. టీమ్ ఇండియా విజయంలో కీలక పాత్ర పోషించింది...',
        'category' => 'Sports',
        'created_at' => '2025-03-29 08:53:11'
    ]
];

// Pagination Mockup
$pager = new class { public function links() { return '<nav><ul class="pagination"><li class="page-item active"><a class="page-link bg-danger border-danger text-white" href="#">1</a></li></ul></nav>'; } };

// Character Limiter Helper
if (!function_exists('character_limiter')) {
    function character_limiter($str, $n) {
        return (mb_strlen($str) > $n) ? mb_substr($str, 0, $n) . '...' : $str;
    }
}
?>

<!DOCTYPE html>
<html lang="te">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NToday News - నేటి వార్తలు</title>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsMediaOrganization",
      "name": "NToday",
      "url": "<?= base_url() ?>",
      "logo": "<?= base_url('assets/images/logo.png') ?>"
    }
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { --ntoday-red: #dc3545; --ntoday-dark: #1a1a1a; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Arial, sans-serif; }
        .navbar { background: var(--ntoday-dark) !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .sidebar { height: 100vh; position: fixed; left: -250px; top: 0; width: 250px; background: var(--ntoday-dark); transition: 0.3s; z-index: 1050; padding-top: 60px; }
        .sidebar.active { left: 0; }
        .breaking-news { background: #fff; border-radius: 50px; overflow: hidden; border: 1px solid #ddd; }
        .breaking-label { background: var(--ntoday-red); color: #fff; padding: 10px 20px; font-weight: bold; }
        .news-card { border: none; transition: 0.3s; border-radius: 12px; overflow: hidden; background: #fff; height: 100%; }
        .news-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .card-title a { color: #222; text-decoration: none; font-weight: bold; }
        .card-title a:hover { color: var(--ntoday-red); }
        @media (prefers-color-scheme: dark) {
            body { background-color: #121212; color: #eee; }
            .card, .news-card, .breaking-news { background-color: #1e1e1e; border-color: #333; }
            .card-title a { color: #fff; }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <a href="#" class="nav-link text-white p-3 border-bottom border-secondary"><i class="fas fa-home me-2"></i> హోమ్</a>
    <a href="#" class="nav-link text-white p-3 border-bottom border-secondary"><i class="fas fa-newspaper me-2"></i> రాజకీయం</a>
    <a href="#" class="nav-link text-white p-3 border-bottom border-secondary"><i class="fas fa-sports-ball me-2"></i> క్రీడలు</a>
</div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <button class="btn btn-outline-light me-2" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
        <a class="navbar-brand" href="#">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="NToday" style="height: 45px;" onerror="this.src='https://via.placeholder.com/150x50?text=NToday'">
        </a>
        <div class="ms-auto d-flex align-items-center">
            <form class="d-flex" action="#">
                <input class="form-control me-2 d-none d-sm-block" type="search" placeholder="వెతకండి...">
                <button class="btn btn-danger" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-3">
    <?php if(!empty($breaking_news)): ?>
    <div class="breaking-news d-flex align-items-center shadow-sm mb-4">
        <div class="breaking-label">బ్రేకింగ్</div>
        <marquee scrollamount="6" class="flex-grow-1 pt-1">
            <?php foreach($breaking_news as $bn): ?>
                <span class="mx-4 fw-bold"> <i class="fas fa-circle-dot text-danger small"></i> <?= esc($bn['title']) ?></span>
            <?php endforeach; ?>
        </marquee>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <h4 class="mb-3 border-start border-4 border-danger ps-2 fw-bold">తాజా వార్తలు</h4>
            <?php foreach($latest_news as $news): ?>
            <div class="card news-card mb-4 shadow-sm">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="https://via.placeholder.com/400x250?text=News+Image" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="<?= esc($news['title']) ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><a href="#"><?= esc($news['title']) ?></a></h5>
                            <p class="card-text text-muted small"><?= character_limiter($news['content'], 120) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-danger border"><?= $news['category'] ?></span>
                                <small class="text-muted"><i class="far fa-clock"></i> <?= date('d M, Y', strtotime($news['created_at'])) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="mt-4"><?= $pager->links() ?></div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-3 mb-4">
                <h5 class="fw-bold border-bottom pb-2 text-danger">ట్రెండింగ్ వార్తలు</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 border-0">
                        <a href="#" class="text-dark text-decoration-none small fw-bold">1. కేసీఆర్ కీలక నిర్ణయం.. పార్టీలో మార్పులు!</a>
                    </li>
                    <li class="list-group-item px-0 border-0">
                        <a href="#" class="text-dark text-decoration-none small fw-bold">2. సినిమా షూటింగ్‌లో ప్రమాదం.. హీరో సేఫ్.</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>