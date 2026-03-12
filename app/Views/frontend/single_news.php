<?= view('frontend/includes/header', ['title' => $news['title']]) ?>

<script>
    (function() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    })();
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Mandali&family=Noto+Sans+Telugu:wght@400;700&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<?php 
    $display_date = (!empty($news['published_at']) && $news['published_at'] != '0000-00-00 00:00:00') 
                    ? $news['published_at'] 
                    : $news['updated_at'];
?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "<?= addslashes($news['title']) ?>",
  "image": ["<?= base_url('uploads/news/'.$news['featured_image']) ?>"],
  "datePublished": "<?= $display_date ?>",
  "dateModified": "<?= $news['updated_at'] ?>",
  "author": [{
      "@type": "Person",
      "name": "<?= esc($news['reporter_name'] ?? 'NToday Team') ?>",
      "url": "<?= base_url('profile/'.$news['slug']) ?>"
    }]
}
</script>

<style>
    :root {
        --ntoday-red: #dc3545;
        --ntoday-dark: #1a1a1a;
        --ntoday-yellow: #ffc107;
        --bg-body: #f8fafc;
        --bg-container: rgba(255, 255, 255, 0.98);
        --text-main: #334155;
        --text-heading: #1a1a1a;
        --text-muted: #64748b;
        --card-bg: #ffffff;
        --card-border: #f1f5f9;
        --border-subtle: rgba(226, 232, 240, 0.8);
        --share-bg: #f8f9fa;
        --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
    }

    [data-theme="dark"] {
        --bg-body: #0b0f13;
        --bg-container: #1c252e;
        --text-main: #cbd5e1;
        --text-heading: #f8fafc;
        --text-muted: #94a3b8;
        --card-bg: #1c252e;
        --card-border: #2d3741;
        --border-subtle: #2d3741;
        --share-bg: #121921;
        --shadow-md: 0 10px 30px rgba(0,0,0,0.3);
    }

    /* Primary Font Updated to Noto Sans Telugu for Telugu content clarity */
    body { background-color: var(--bg-body); font-family: 'Noto Sans Telugu', 'Inter', sans-serif; color: var(--text-main); transition: background-color 0.3s, color 0.3s; }
    
    .article-container { background: var(--bg-container); border: 1px solid var(--border-subtle); border-radius: 20px; padding: 40px; box-shadow: var(--shadow-md); max-width: 100%; overflow: hidden; }
    
    .news-title { font-family: 'Mandali', sans-serif; color: var(--text-heading); font-weight: 800; line-height: 1.25; margin-bottom: 25px; font-size: 2.4rem; word-wrap: break-word; }
    
    /* Content text specifically using Noto Sans Telugu for high readability */
    .news-content { line-height: 2; font-size: 1.2rem; color: var(--text-main); word-wrap: break-word; font-family: 'Noto Sans Telugu', sans-serif; }
    
    .news-content img { max-width: 100% !important; height: auto !important; border-radius: 12px; margin: 25px 0; display: block; }
    .featured-img-wrapper { width: 100%; max-height: 500px; overflow: hidden; border-radius: 16px; margin-bottom: 30px; }
    .featured-img-wrapper img { width: 100%; height: auto; object-fit: cover; }
    .sidebar-title { font-family: 'Mandali', sans-serif; border-bottom: 2px solid var(--ntoday-red); display: inline-block; padding-bottom: 5px; margin-bottom: 20px; font-weight: 800; color: var(--text-heading); }
    .trending-item { padding: 12px 0; border-bottom: 1px solid var(--card-border); text-decoration: none !important; transition: all 0.3s ease; }
    .trending-number { font-size: 1.2rem; font-weight: 800; color: var(--ntoday-red); opacity: 0.4; min-width: 25px; }
    .trending-card-title { display: block; font-family: 'Mandali', sans-serif; font-size: 0.95rem; font-weight: 700; color: var(--text-heading); line-height: 1.4; }
    .trending-img-wrapper { width: 75px; height: 55px; overflow: hidden; border-radius: 6px; border: 1px solid var(--card-border); }
    .trending-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .recent-news-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 16px; padding: 12px; margin-bottom: 16px; transition: all 0.3s ease; display: flex; align-items: center; text-decoration: none !important; }
    .recent-card-img-wrapper { width: 90px; height: 70px; flex-shrink: 0; overflow: hidden; border-radius: 10px; }
    .recent-card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .sticky-sidebar { position: sticky; top: 110px; z-index: 10; }

    .category-badge { 
        background: var(--ntoday-red); color: white; padding: 6px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); border-left: 4px solid rgba(0,0,0,0.2);
    }
    .meta-item { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; color: var(--text-muted); font-weight: 500; }
    .meta-item i { color: var(--ntoday-red); font-size: 1rem; }

    /* --- Screenshot Specific Styles --- */
    #capture-header { background: #fff; border-bottom: 4px solid var(--ntoday-red); padding: 20px; border-radius: 20px 20px 0 0; }
    #capture-footer { background: #f8f9fa; border-top: 1px solid #eee; padding: 20px; border-radius: 0 0 20px 20px; }
    .ss-logo { height: 45px; object-fit: contain; }
    .ss-time { font-size: 12px; color: #555; font-weight: 600; }
    
    /* Double Column Layout classes */
    .ss-grid { display: flex !important; gap: 30px; align-items: flex-start; }
    .ss-col-img { flex: 0 0 45% !important; }
    .ss-col-text { flex: 1 !important; }

    .comment-box { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 15px; padding: 20px; margin-top: 30px; }
    .comment-input { border-radius: 10px; border: 1px solid var(--border-subtle); background: var(--bg-body); color: var(--text-main); }

    @media (max-width: 768px) {
        .article-container { padding: 25px 20px; border-radius: 0; margin-top: -25px; }
        .news-title { font-size: 1.8rem; margin-bottom: 15px; }
        .sticky-sidebar { position: static; margin-top: 30px; }
    }
</style>

<div class="container mt-0 mt-md-5 pb-5">
    <div class="row g-4">
        <aside class="col-lg-3 order-3 order-lg-1">
            <div class="sticky-sidebar">
                <h5 class="sidebar-title">ట్రెండింగ్ వార్తలు</h5>
                <?php if(!empty($trending_news)): $i=1; foreach($trending_news as $trend): ?>
                <a href="<?= base_url('news/'.$trend['slug']) ?>" class="trending-item d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-start flex-grow-1 me-2">
                        <span class="trending-number me-2"><?= sprintf("%02d", $i++) ?></span>
                        <div class="trending-info">
                            <span class="trending-card-title"><?= mb_substr($trend['title'], 0, 50) ?>...</span>
                        </div>
                    </div>
                    <div class="trending-img-wrapper flex-shrink-0">
                        <img src="<?= (!empty($trend['featured_image'])) ? base_url('uploads/news/'.$trend['featured_image']) : base_url('assets/images/default.png'); ?>" alt="Trending">
                    </div>
                </a>
                <?php endforeach; endif; ?>
            </div>
        </aside>

        <main class="col-lg-6 col-md-12 order-1 order-lg-2">
            <div id="capture-container" style="background: #fff; width: 100%; max-width: 900px; margin: auto;">
                
                <div id="capture-header" class="d-none d-flex justify-content-between align-items-center">
                    <div class="ss-time">
                        <div id="ss-date-display"></div>
                        <div class="text-danger">www.ntoday.in</div>
                    </div>
                    <img src="<?= base_url('assets/images/Logo-R.png') ?>" class="ss-logo" alt="Logo">
                </div>

                <div class="article-container" id="capture-body" style="box-shadow: none; border: none;">
                    <nav aria-label="breadcrumb" class="mb-2 no-screenshot">
                        <ol class="breadcrumb small bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-danger text-decoration-none fw-bold">హోమ్</a></li>
                            <li class="breadcrumb-item active fw-semibold text-muted">వార్తలు</li>
                        </ol>
                    </nav>

                    <h1 class="news-title"><?= esc($news['title']) ?></h1>
                    
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4 pb-4 border-bottom">
                        <div class="d-flex align-items-center me-2">
                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 42px; height: 42px;">
                                <i class="fa-solid fa-user-pen"></i>
                            </div>
                            <div>
                                <span class="fw-bold d-block text-dark" style="line-height: 1.2; font-size: 1rem;"><?= esc($news['reporter_name'] ?? 'NToday బృందం') ?></span>
                                <small class="text-danger fw-bold" style="font-size: 0.75rem;"><?= esc($news['area_coverage'] ?? 'NToday Special') ?></small>
                            </div>
                        </div>
                        <div class="meta-item"><i class="fa-regular fa-calendar-check"></i> <?= date('d M, Y', strtotime($display_date)) ?></div>
                        <div class="meta-item ms-auto no-screenshot"><i class="fa-regular fa-eye"></i> <?= number_format($news['view_count'] ?? 0) ?></div>
                    </div>

                    <div id="dynamic-layout-row">
                        <div class="featured-img-wrapper" id="ss-img-box">
                            <img id="main-news-image" src="<?= (!empty($news['featured_image'])) ? base_url('uploads/news/'.$news['featured_image']) : base_url('assets/images/default.png'); ?>" crossorigin="anonymous" class="img-fluid rounded-4">
                        </div>

                        <div id="ss-text-box">
                            <div class="mb-3 d-flex flex-wrap gap-2">
                                <span class="category-badge"><?= esc($news['category_name'] ?? 'వార్తలు') ?></span>
                                <?php if(!empty($news['mandal_name'])): ?>
                                    <span class="category-badge bg-info text-white"><?= esc($news['mandal_name']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="news-content">
                                <?= $news['content'] ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="capture-footer" class="d-none">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h6 class="fw-bold text-dark mb-1">NToday - నిజం వెంటే నిరంతరం!</h6>
                            <p class="small text-muted mb-0">తాజా వార్తల కోసం ప్లే స్టోర్ లో మా యాప్ డౌన్లోడ్ చేసుకోండి.</p>
                        </div>
                        <div class="col-4 text-end">
                            <span class="badge bg-danger p-2">LIVE UPDATES 24/7</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 p-4 rounded-4 share-container d-flex flex-column flex-md-row align-items-center justify-content-between no-screenshot" style="background: var(--share-bg); border: 1px dashed var(--ntoday-red);">
                <span class="fw-bold text-muted small"><i class="fa-solid fa-share-nodes me-2 text-danger"></i> షేర్ చేయండి:</span>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <a href="https://api.whatsapp.com/send?text=<?= rawurlencode($news['title'] . ' ' . current_url()) ?>" target="_blank" class="btn btn-success btn-sm rounded-pill px-4"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= current_url() ?>" target="_blank" class="btn btn-primary btn-sm rounded-pill px-4"><i class="fa-brands fa-facebook-f"></i> Facebook</a>
                    
                    <div class="dropdown">
                        <button class="btn btn-dark btn-sm rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-download me-1"></i> Save Image
                        </button>
                        <ul class="dropdown-menu shadow border-0">
                            <li><a class="dropdown-item fw-bold py-2" href="javascript:void(0)" onclick="handleDownload('single')">Single Column (Vertical)</a></li>
                            <li><a class="dropdown-item fw-bold py-2 border-top" href="javascript:void(0)" onclick="handleDownload('double')">Double Column (Wide)</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="comment-box no-screenshot shadow-sm">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Comments</h5>
                <form action="#" method="POST">
                    <div class="mb-3">
                        <textarea class="form-control comment-input" rows="3" placeholder="మీ అభిప్రాయాన్ని తెలపండి..."></textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-danger btn-sm fw-bold rounded-pill px-4">Post Comment</button>
                    </div>
                </form>
            </div>

            <div class="mt-5 pt-4 border-top">
                <h4 class="sidebar-title">మరిన్ని వార్తలు (Related)</h4>
                <div class="row g-3">
                    <?php 
                        $db = \Config\Database::connect();
                        $related = $db->table('news')->where('category_id', $news['category_id'])->where('id !=', $news['id'])->where('status', 1)->orderBy('published_at', 'DESC')->limit(4)->get()->getResultArray();
                        foreach($related as $rel): 
                            $rel_img = (!empty($rel['featured_image'])) ? base_url('uploads/news/'.$rel['featured_image']) : base_url('assets/images/default.png');
                    ?>
                    <div class="col-6 col-md-4 col-lg-6">
                        <a href="<?= base_url('news/'.$rel['slug']) ?>" class="text-decoration-none">
                            <div class="position-relative overflow-hidden rounded-3 mb-2" style="aspect-ratio: 16/9; background: #eee;">
                                <img src="<?= $rel_img ?>" class="w-100 h-100 object-fit-cover transition-all" alt="Related">
                            </div>
                            <h6 class="small fw-bold text-dark line-clamp-2" style="font-family: 'Mandali', sans-serif; line-height: 1.3;">
                                <?= esc($rel['title']) ?>
                            </h6>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <aside class="col-lg-3 col-md-12 order-2 order-lg-3">
            <div class="sticky-sidebar">
                <h5 class="sidebar-title">తాజా వార్తలు</h5>
                <?php if(!empty($latest_news)): foreach($latest_news as $recent): ?>
                <a href="<?= base_url('news/'.$recent['slug']) ?>" class="recent-news-card shadow-sm">
                    <div class="recent-card-img-wrapper">
                        <img src="<?= (!empty($recent['featured_image'])) ? base_url('uploads/news/'.$recent['featured_image']) : base_url('assets/images/default.png'); ?>" alt="Recent">
                    </div>
                    <div class="ps-3">
                        <div class="recent-card-title fw-bold mb-1" style="font-family: 'Mandali', sans-serif; font-size: 0.95rem;"><?= mb_substr($recent['title'], 0, 45) ?>...</div>
                    </div>
                </a>
                <?php endforeach; endif; ?>
            </div>
        </aside>
    </div>
</div>



<script>
    function toggleTheme() {
        const html = document.documentElement;
        const icon = document.getElementById('theme-icon');
        if (html.getAttribute('data-theme') === 'dark') {
            html.removeAttribute('data-theme');
            if(icon) icon.classList.replace('fa-sun', 'fa-moon');
            localStorage.setItem('theme', 'light');
        } else {
            html.setAttribute('data-theme', 'dark');
            if(icon) icon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('theme', 'dark');
        }
    }

    // --- ENHANCED DOWNLOAD LOGIC ---
    async function handleDownload(mode) {
        const container = document.getElementById('capture-container');
        const header = document.getElementById('capture-header');
        const footer = document.getElementById('capture-footer');
        const bodyLayout = document.getElementById('dynamic-layout-row');
        const imgBox = document.getElementById('ss-img-box');
        const textBox = document.getElementById('ss-text-box');
        const noScreenshots = document.querySelectorAll('.no-screenshot');
        
        // 1. Set current timestamp
        const now = new Date();
        document.getElementById('ss-date-display').innerText = now.toLocaleString('te-IN', { dateStyle: 'long', timeStyle: 'short' });

        // 2. Prep visibility
        header.classList.remove('d-none');
        footer.classList.remove('d-none');
        noScreenshots.forEach(el => el.style.visibility = 'hidden');

        // 3. Switch Layout
        if(mode === 'double') {
            container.style.width = '1000px';
            bodyLayout.classList.add('ss-grid');
            imgBox.classList.add('ss-col-img');
            textBox.classList.add('ss-col-text');
        } else {
            container.style.width = '600px';
            bodyLayout.classList.remove('ss-grid');
            imgBox.classList.remove('ss-col-img');
            textBox.classList.remove('ss-col-text');
        }

        // Wait for re-render
        window.scrollTo(0,0);
        setTimeout(() => {
            html2canvas(container, {
                useCORS: true,
                scale: 3, // High resolution
                backgroundColor: "#ffffff",
                logging: false
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `NToday-${mode}-News.png`;
                link.href = canvas.toDataURL('image/png', 1.0);
                link.click();

                // 4. Restore original view
                header.classList.add('d-none');
                footer.classList.add('d-none');
                container.style.width = '';
                bodyLayout.classList.remove('ss-grid');
                imgBox.classList.remove('ss-col-img');
                textBox.classList.remove('ss-col-text');
                noScreenshots.forEach(el => el.style.visibility = 'visible');
            });
        }, 600);
    }
</script>

<?= view('frontend/includes/footer') ?>