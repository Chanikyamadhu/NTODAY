<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<?= view('frontend/includes/header', ['title' => 'NToday - హోమ్']) ?>
<link href="https://fonts.googleapis.com/css2?family=Mandali&display=swap" rel="stylesheet">

<style>
    /* 1. THEME VARIABLES */
    :root {
        --bg-body: #f8fafc;
        --bg-main: #ffffff;
        --text-main: #1a1a1a;
        --text-muted: #64748b;
        --border-color: #eee;
        --trending-num: rgba(0,0,0,0.1);
        --video-bg: #f1f5f9;
        --accent-red: #dc3545;
        --modal-bg: rgba(0, 0, 0, 0.9);
        --banner-bg: #e2e8f0;
    }

    [data-theme="dark"] {
        --bg-body: #0f172a;
        --bg-main: #1e293b;
        --text-main: #f1f5f9;
        --text-muted: #94a3b8;
        --border-color: #334155;
        --trending-num: rgba(255,255,255,0.1);
        --video-bg: #334155;
        --banner-bg: #1e293b;
    }

    body { 
        background-color: var(--bg-body); 
        color: var(--text-main); 
        transition: background 0.3s ease, color 0.3s ease;
    }
     
    /* Swiper Slider Styling (16:9 Aspect Ratio) */
    .slider-image-container {
        width: 100%;
        aspect-ratio: 1200 / 675; /* image_9c6a42.png స్టైల్ */
        overflow: hidden;
    }
    .slider-img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .slider-title-bottom {
        font-family: 'Noto Sans Telugu', sans-serif;
        font-size: 1.5rem; font-weight: 700; color: #1a1a1a;
        line-height: 1.4; transition: color 0.3s;
    }
    .slider-title-bottom:hover { color: #dc3545; }
    
    /* 3. TRENDING STYLING */
    .post-meta { font-size: 0.75rem; color: var(--text-muted); display: flex; gap: 10px; align-items: center; }
    .trending-item { background: transparent; border-bottom: 1px solid var(--border-color) !important; padding: 12px 0 !important; }
    .trending-img { width: 90px; height: 65px; object-fit: cover; border-radius: 4px; }
    .trending-num-text { color: var(--trending-num); font-weight: 900; }

    /* 4. VIDEO SECTION GAP FIX */
    .videoSwiper { padding-bottom: 0px !important; margin-bottom: 0px !important; }
    .videoSwiper .swiper-pagination {
        position: relative !important;
        bottom: 0 !important;
        margin-top: 10px !important;
    }

    .video-card { cursor: pointer; transition: transform 0.3s; }
    .video-card:hover { transform: scale(1.02); }
    .video-thumb-wrapper { position: relative; height: 160px; overflow: hidden; border-radius: 12px; background: var(--video-bg); }
    .play-btn-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;
        font-size: 3rem; color: white; transition: 0.3s;
    }
    
    /* 5. TOP BANNER STYLING */
    .top-banner-40 {
        width: 100%;
        height: 100px;
        background-color: var(--banner-bg);
        border: 1px solid var(--border-color);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        overflow: hidden;
    }

    /* Video Modal */
    .video-modal {
        display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%;
        background: var(--modal-bg); align-items: center; justify-content: center;
    }
    .modal-content-inner { width: 90%; max-width: 800px; position: relative; aspect-ratio: 16/9; }
    .close-modal { position: absolute; top: -40px; right: 0; color: white; font-size: 2.5rem; cursor: pointer; }

    /* Category Styling */
    .category-btn { 
        color: var(--text-main); background: var(--bg-main); border: 1px solid var(--border-color);
        padding: 5px 15px; border-radius: 20px; text-decoration: none; transition: 0.3s;
        white-space: nowrap;
    }
    .category-btn:hover { background: var(--accent-red); color: #fff; }
    .cat-header { border-bottom: 2px solid var(--text-main); margin-bottom: 20px; padding-bottom: 5px; }
    .cat-news-img { width: 70px; height: 50px; object-fit: cover; border-radius: 4px; }
    
    h4, h5, h6 { color: var(--text-main) !important; }
    .shadow-sm, .border { box-shadow: none !important; border: none !important; }

    .top-banner-40 {
        width: 100%;
        max-width: 1320px;
        /* డెస్క్‌టాప్‌లో 180px ఎత్తు బాగుంటుంది */
        height: 180px;     
        margin: 15px auto;
        overflow: hidden;
        display: block;
        background-color: #f1f5f9; /* ఇమేజ్ లోడ్ అయ్యే వరకు ఖాళీగా కనిపించకుండా */
    }

    .top-banner-40 img {
        width: 100%;
        height: 100%;
        /* 'fill' కి బదులు 'cover' వాడండి, దీనివల్ల ఇమేజ్ సాగదీసినట్లు (stretch) అనిపించదు */
        object-fit: cover; 
    }

    /* మొబైల్ ఫోన్ల కోసం (Tablets & Phones) */
    @media (max-width: 768px) {
        .top-banner-40 {
            /* మొబైల్‌లో 1320/180 (7.3:1) చాలా సన్నగా ఉంటుంది. 
               అందుకే మొబైల్‌లో కొంచెం ఎక్కువ ఎత్తు కనిపించడానికి 4:1 లేదా 3:1 వాడటం ఉత్తమం */
            height: 180;
            aspect-ratio: 3 / 1.1; 
            margin: 10px auto;
        }
        
        .top-banner-40 img {
            /* మొబైల్‌లో ఇమేజ్ లోని ముఖ్యాంశం (Text) సెంటర్‌లో ఉండేలా చేస్తుంది */
            object-position: center; 
        }
    }
</style>

<main class="container">
    <div class="category-slider mt-2 d-flex overflow-auto pb-2 gap-2" style="scrollbar-width: none; -ms-overflow-style: none;">
    <a href="<?= base_url() ?>" class="category-btn small">అన్నీ (All)</a>

    <?php if(!empty($categories)): ?>
        <?php foreach($categories as $cat): ?>
            <?php 
                // కేవలం ఆక్టివ్ (1) మరియు టైప్ 'main' లేదా 'state' అయితేనే ప్రదర్శించు
                if($cat['IsActice'] == 1 && ($cat['type'] == 'state' || $cat['type'] == 'main')): 
            ?>
                <a href="<?= base_url('category/'.$cat['id']) ?>" class="category-btn small">
                    <?= esc($cat['name']) ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

            <div class="top-banner-40 shadow-sm rounded overflow-hidden">
        <?php if (!empty($top_banner)): ?>
            <a href="<?= esc($top_banner['link_url'] ?? '#') ?>" target="_blank" class="d-block w-100 h-100">
                <img src="<?= base_url('uploads/banners/' . $top_banner['image_path']) ?>" 
                    alt="<?= esc($top_banner['title']) ?>" 
                    class="img-fluid w-100 h-100" 
                    style="object-fit: fill; min-height: 180px;"> 
                    </a>
        <?php else: ?>
            <a href="#" class="d-block w-100 h-100">
                <img src="<?= base_url('assets/images/default-banner.jpg') ?>" 
                    alt="Advertise with Us" 
                    class="img-fluid w-100 h-100 opacity-50" 
                    style="object-fit: cover; min-height: 180px;">
            </a>
        <?php endif; ?>
    </div>

   <div class="row g-4 mt-1">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold border-start border-5 border-danger ps-2 mb-0 text-slate-800">తాజా వార్తలు</h4>
            <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-soft-danger text-danger border border-danger small animate-flash px-3 py-2">
                    <span class="pulse-dot me-1"></span> Live Updates
                </span>
            </div>
        </div>

        <div class="swiper mySwiper shadow-lg rounded-4 overflow-hidden bg-white border">
            <div class="swiper-wrapper">
                <?php if(!empty($latest_news)): 
                    $ad_counter = 0;
                    $ad_index = 0; 
                    $total_real_ads = count($slide_ads ?? []);
                    
                    foreach(array_slice($latest_news, 0, 10) as $news): 
                        $ad_counter++;
                        
                        // Default Image Logic: ఇమేజ్ లేకపోతే default.jpg చూపిస్తుంది
                        $image_path = (!empty($news['featured_image'])) 
                                      ? base_url('uploads/news/'.$news['featured_image']) 
                                      : base_url('assets/images/default.png'); // మీ డెఫాల్ట్ ఇమేజ్ పాత్ ఇక్కడ ఇవ్వండి
                ?>
                    <div class="swiper-slide bg-white">
                        <a href="<?= base_url('news/'.$news['slug']) ?>" class="text-decoration-none group">
                            <div class="slider-image-container position-relative overflow-hidden">
                                <img src="<?= $image_path ?>" 
                                     class="slider-img transition-all duration-500" alt="<?= esc($news['title']) ?>">
                                
                                <span class="position-absolute top-0 start-0 badge bg-danger m-3 shadow-sm px-3 py-2">Latest News</span>
                            </div>
                            <div class="p-3 bg-white">
                                <h2 class="slider-title-text h5 fw-bold mb-2 text-dark line-clamp-2"><?= esc($news['title']) ?></h2>
                                <div class="slider-meta-info d-flex align-items-center small text-muted font-monospace">
                                    <span class="me-3"><i class="bi bi-clock me-1 text-danger"></i> <?= date('d M, Y', strtotime($news['published_at'])) ?></span>
                                    <span><i class="bi bi-eye me-1 text-primary"></i> <?= number_format($news['view_count'] ?? 0) ?> వ్యూస్</span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <?php if($ad_counter % 3 == 0): ?>
                        <div class="swiper-slide bg-light border-start border-end">
                            <?php if($total_real_ads > 0 && isset($slide_ads[$ad_index])): 
                                $current_ad = $slide_ads[$ad_index % $total_real_ads]; 
                                $img_file   = $current_ad['image_path'] ?? ''; 
                                $ad_link    = $current_ad['link_url'] ?? base_url('contact-us');
                                $ad_title   = $current_ad['title'] ?? 'Special Offer'; 
                                $ad_summary = $current_ad['summary'] ?? 'Discover more about our services';
                            ?>
                                <a href="<?= esc($ad_link) ?>" target="_blank" class="text-decoration-none d-block h-100 group">
                                    <div class="slider-image-container position-relative overflow-hidden">
                                        <img src="<?= base_url('uploads/banners/' . $img_file) ?>" 
                                             alt="Ad" class="slider-img object-fit-cover">
                                        
                                        <span class="position-absolute top-0 end-0 badge bg-dark opacity-75 m-3 shadow-sm">Sponsored</span>
                                        
                                        <div class="position-absolute bottom-0 end-0 m-3 animate-slide-up">
                                            <div class="learn-more-polished d-flex align-items-center px-3 py-2 rounded-pill fw-bold shadow-lg">
                                                Learn More <i class="bi bi-arrow-right-short ms-1 fs-5"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-light text-center border-top h-100">
                                        <h6 class="fw-bold text-slate-800 mb-1 text-truncate"><?= esc($ad_title) ?></h6>
                                        <p class="small text-muted mb-0 text-truncate opacity-75"><?= esc($ad_summary) ?></p>
                                    </div>
                                </a>
                                <?php $ad_index++; ?>
                            <?php else: ?>
                                <div class="slider-image-container d-flex flex-column align-items-center justify-content-center bg-white border-dashed h-100">
                                    <div class="ad-icon mb-2 text-danger opacity-50"><i class="bi bi-megaphone fs-1"></i></div>
                                    <h5 class="fw-bold text-dark mb-1">మీ ప్రకటన ఇక్కడ!</h5>
                                    <p class="text-muted small px-4 mb-3 text-center">లక్షలాది మంది పాఠకులకు మీ వ్యాపారాన్ని చేరవేయండి.</p>
                                    <a href="<?= base_url('contact-us') ?>" class="btn btn-danger btn-sm rounded-pill px-4 shadow-sm fw-bold">ప్రకటన ఇవ్వండి</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php endforeach; endif; ?>
            </div>  
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next custom-nav shadow-sm"></div>
            <div class="swiper-button-prev custom-nav shadow-sm"></div>
        </div>
    </div>

<style>
    /* Fixed Aspect Ratio 16:9 */
    .slider-image-container { 
        width: 100%; 
        aspect-ratio: 16 / 9; 
        background-color: #f8f9fa;
        display: block;
    } 
    
    .slider-img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    
    .swiper-slide:hover .slider-img { transform: scale(1.05); }

    /* Polished Learn More Button on Image */
    .learn-more-polished {
        background: rgba(220, 53, 69, 0.95); /* News Theme Red */
        color: white;
        font-size: 11px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .group:hover .learn-more-polished {
        background: #000;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3) !important;
    }

    /* Animation for the button appearance */
    .animate-slide-up {
        animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
        from { transform: translateY(10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Meta & Typography */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .animate-flash { animation: flash 2s infinite; }
    @keyframes flash { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

    .pulse-dot { height: 9px; width: 9px; background-color: #dc3545; border-radius: 50%; display: inline-block; animation: pulse 1.8s infinite; }
    @keyframes pulse { 
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); } 
        70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(220, 53, 69, 0); } 
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); } 
    }

    .custom-nav { 
        width: 44px !important; 
        height: 44px !important; 
        background: rgba(255, 255, 255, 0.98); 
        border-radius: 50%; 
        top: 38% !important; 
        border: 1px solid #eee;
    }
    .custom-nav::after { font-size: 16px !important; font-weight: 900; color: #dc3545; }
    .custom-nav:hover { background: #dc3545; color: white; }
    .custom-nav:hover::after { color: white; }
    
    .border-dashed { border: 2px dashed #dee2e6; margin: 10px; border-radius: 15px; }
    .text-slate-800 { color: #1e293b; }
</style>

        <div class="col-lg-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold border-start border-5 border-danger ps-2 mb-0">ట్రెండింగ్</h4>
        <a href="<?= base_url('trending') ?>" class="text-danger small fw-bold text-decoration-none">Explore All +</a>
    </div>
    <div class="trending-list">
        <?php if(!empty($trending_news)): 
            $i = 1;
            foreach($trending_news as $trend): 
                // Image handling logic
                $trend_image = (!empty($trend['featured_image'])) 
                               ? base_url('uploads/news/'.$trend['featured_image']) 
                               : base_url('assets/images/default.png'); 
        ?>
            <a href="<?= base_url('news/'.$trend['slug']) ?>" class="text-decoration-none">
                <div class="trending-item d-flex align-items-center cursor-pointer">
                    <span class="h4 trending-num-text me-3 mb-0" style="min-width: 25px;"><?= $i++ ?></span>
                    <img src="<?= $trend_image ?>" class="trending-img me-3" alt="Trending">
                    <div class="flex-grow-1">
                        <p class="small fw-bold mb-1 text-dark" style="line-height: 1.3; color: var(--text-main) !important;"><?= mb_substr(esc($trend['title']), 0, 70) ?>...</p>
                        <div class="post-meta" style="font-size: 0.65rem;">
                            <span><i class="far fa-clock"></i> <?= date('d M, Y', strtotime($trend['published_at'] ?? $trend['created_at'])) ?></span>
                            <span><i class="far fa-eye"></i> <?= number_format($trend['view_count'] ?? 0) ?></span>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; endif; ?>
    </div>
</div>

    <section class="mt-5 pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold border-start border-5 border-danger ps-2">వీడియో ప్లేలిస్ట్</h4>
        <a href="https://www.youtube.com/channel/UCti6SpfXgaHXbmNHKyX2GJA" target="_blank" class="text-danger fw-bold text-decoration-none small">
            YouTube Channel <i class="fab fa-youtube"></i>
        </a>
    </div>
    
    <div class="swiper videoSwiper">
        <div class="swiper-wrapper">
            <?php if(!empty($youtube_videos)): foreach($youtube_videos as $video): 
                // 1. మెరుగైన వీడియో ఐడి ఎక్స్‌ట్రాక్షన్ (Shorts, Watch, Embed అన్నింటికీ పనిచేస్తుంది)
                $vid_id = $video['video_id'] ?? '';
                if(empty($vid_id)) {
                    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
                    if (preg_match($pattern, $video['link'], $match)) {
                        $vid_id = $match[1];
                    }
                }

                // 2. థంబ్‌నెయిల్ బ్యాకప్ లాజిక్
                $thumb_url = !empty($video['thumbnail']) ? $video['thumbnail'] : "https://img.youtube.com/vi/{$vid_id}/mqdefault.jpg";
            ?>
            <div class="swiper-slide h-auto">
                <?php if(!empty($vid_id)): ?>
                <div class="video-card" onclick="playVideo('<?= $vid_id ?>')"> 
                    <div class="video-thumb-wrapper shadow-sm overflow-hidden rounded-4">
                        <img src="<?= $thumb_url ?>" class="w-100 h-100 object-fit-cover" 
                             onerror="this.src='https://img.youtube.com/vi/<?= $vid_id ?>/hqdefault.jpg'">
                        <div class="play-btn-overlay"><i class="fa-brands fa-youtube"></i></div>
                    </div>
                    <p class="small fw-bold mt-2 text-truncate-2" style="color: var(--text-main); font-family: 'Mandali', sans-serif; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= esc($video['title']) ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; else: ?>
                <div class="col-12 text-center py-5 bg-light rounded-4 border border-dashed">
                    <i class="fas fa-video-slash mb-3 d-block opacity-25 fs-1"></i>
                    <p class="text-muted mb-0 small">వీడియోలు లోడ్ అవ్వలేదు. దయచేసి రీఫ్రెష్ చేయండి.</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="swiper-pagination mt-4"></div>
    </div>
</section>


    <div id="videoModal" class="video-modal" onclick="closeVideo()">
        <div class="modal-content-inner" onclick="event.stopPropagation()">
            <span class="close-modal" onclick="closeVideo()">&times;</span>
            <iframe id="videoPlayer" width="100%" height="100%" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>


    </section>

<section class="mt-4 mb-5">
    <div class="weather-widget-container shadow-sm rounded-4 overflow-hidden border transition-all">
        <div class="row g-0 align-items-center bg-white">
            <div class="col-md-4 bg-danger text-white p-3 d-flex align-items-center justify-content-center flex-column border-end border-white border-opacity-25">
                <h6 class="fw-bold mb-1 opacity-75 small text-uppercase ls-1">నేటి వాతావరణం</h6>
                <div id="weather-icon-div" class="fs-1 mb-1">
                    <i class="bi bi-cloud-sun"></i>
                </div>
                <div class="h3 fw-bold mb-0" id="temp-display">--°C</div>
            </div>
            
            <div class="col-md-8 p-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <h5 class="fw-bold mb-0 text-slate-800" id="city-display"><i class="bi bi-geo-alt-fill text-danger me-1"></i> లోడ్ అవుతోంది...</h5>
                        <p class="small text-muted mb-0" id="weather-desc">వాతావరణ వివరాలు సేకరిస్తున్నాము</p>
                    </div>
                    <div class="d-flex gap-3 border-start ps-md-3">
                        <div class="text-center">
                            <small class="text-muted d-block small">గాలి తేమ</small>
                            <span class="fw-bold small" id="humidity-display">--%</span>
                        </div>
                        <div class="text-center">
                            <small class="text-muted d-block small">గాలి వేగం</small>
                            <span class="fw-bold small" id="wind-display">-- km/h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    /* Weather Widget Custom CSS */
    .weather-widget-container {
        max-width: 100%;
        transition: transform 0.3s ease;
    }
    .weather-widget-container:hover {
        transform: translateY(-3px);
    }
    #weather-icon-div i {
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }
    [data-theme="dark"] .weather-widget-container .bg-white {
        background-color: var(--bg-main) !important;
    }
    @media (max-width: 768px) {
        .weather-widget-container .border-end { border-end: none !important; border-bottom: 1px solid rgba(255,255,255,0.2) !important; }
        .weather-widget-container .border-start { border-start: none !important; margin-top: 10px; width: 100%; justify-content: space-around; padding-top: 10px; border-top: 1px solid #eee; }
    }
</style>



    <div class="row mt-5 g-4 g-lg-5">
    <?php 
    if(!empty($categories)): 
        $display_count = 0; // ప్రదర్శించిన కేటగిరీల సంఖ్యను ట్రాక్ చేయడానికి
        foreach($categories as $category): 
            if($display_count >= 4) break; // కేవలం 4 కేటగిరీల వరకు మాత్రమే

            $cat_id = $category['id'];
            $db = \Config\Database::connect();
            $cat_news = $db->table('news')
                           ->where('category_id', $cat_id)
                           ->where('status', 1)
                           ->orderBy('published_at', 'DESC')
                           ->limit(4)
                           ->get()
                           ->getResultArray();

            // ఒకవేళ ఈ కేటగిరీలో వార్తలు ఉంటేనే కింద ఉన్న కోడ్ రన్ అవుతుంది
            if(!empty($cat_news)):
                $display_count++; // వార్తలు ఉన్నాయి కాబట్టి కౌంట్ పెంచుతున్నాం
                $first = $cat_news[0];

                // మొదటి వార్త ఇమేజ్ చెక్
                $first_image = (!empty($first['featured_image'])) 
                               ? base_url('uploads/news/'.$first['featured_image']) 
                               : base_url('assets/images/default.png');
    ?>
    <div class="col-md-6 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-2 border-danger">
            <h5 class="fw-bold mb-0 text-uppercase ls-1" style="color: var(--text-main);">
                <?= esc($category['name']) ?>
            </h5>
            <a href="<?= base_url('category/'.$category['id']) ?>" class="text-danger small fw-bold text-decoration-none hover-link">
                మరిన్ని వార్తలు <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i>
            </a>
        </div>

        <div class="cat-content">
            <div class="card border-0 bg-transparent mb-4 overflow-hidden group">
                <a href="<?= base_url('news/'.$first['slug']) ?>" class="text-decoration-none">
                    <div class="position-relative overflow-hidden rounded-4 mb-3 shadow-sm">
                        <img src="<?= $first_image ?>" 
                             class="img-fluid w-100 transition-transform duration-500 hover-scale" 
                             style="height:230px; object-fit:cover;"
                             alt="<?= esc($first['title']) ?>">
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-danger rounded-pill px-3">Top Story</span>
                        </div>
                    </div>
                    <h6 class="fw-bold fs-5 mb-2 transition-color hover-red" style="color: var(--text-main); line-height: 1.4;">
                        <?= esc($first['title']) ?>
                    </h6>
                    <div class="d-flex align-items-center gap-3 text-muted small">
                        <span><i class="far fa-calendar-alt me-1"></i> <?= date('d M, Y', strtotime($first['published_at'])) ?></span>
                        <span><i class="far fa-eye me-1"></i> <?= number_format($first['view_count'] ?? 0) ?></span>
                    </div>
                </a>
            </div>
            
            <div class="list-group list-group-flush gap-2">
                <?php for($j=1; $j < count($cat_news); $j++): 
                    $item = $cat_news[$j]; 
                    // లిస్ట్ ఐటమ్ ఇమేజ్ చెక్
                    $item_image = (!empty($item['featured_image'])) 
                                  ? base_url('uploads/news/'.$item['featured_image']) 
                                  : base_url('assets/images/default.png');
                ?>
                <a href="<?= base_url('news/'.$item['slug']) ?>" 
                   class="list-group-item list-group-item-action bg-transparent border-0 px-0 d-flex align-items-start gap-3 py-2 rounded-3 hover-bg-light transition-all">
                    <div class="flex-shrink-0">
                        <img src="<?= $item_image ?>" 
                             class="rounded-3 shadow-sm" 
                             style="width: 100px; height: 70px; object-fit: cover;"
                             alt="Thumb">
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 small fw-bold text-truncate-2" style="color: var(--text-main); line-height: 1.4;">
                            <?= esc($item['title']) ?>
                        </p>
                        <span class="text-muted" style="font-size: 0.75rem;">
                            <i class="far fa-clock me-1"></i> <?= date('d M, Y', strtotime($item['created_at'])) ?>
                        </span>
                    </div>
                </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <?php 
            endif; // empty($cat_news) చెక్ ముగింపు
        endforeach; 
    endif; 
    ?>
</div>
</main>

<style>
    /* అదనపు UI ఫినిషింగ్ టచ్‌లు */
    .hover-scale { transition: transform 0.5s ease; }
    .hover-scale:hover { transform: scale(1.05); }
    .hover-red:hover { color: var(--accent-red) !important; }
    .hover-bg-light:hover { background: rgba(0,0,0,0.03) !important; padding-left: 8px !important; }
    [data-theme="dark"] .hover-bg-light:hover { background: rgba(255,255,255,0.05) !important; }
    .ls-1 { letter-spacing: 0.5px; }
    .transition-all { transition: all 0.3s ease; }
</style>

<script>
    // 1. Initialize Main News Slider
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: { delay: 4500, disableOnInteraction: false },
        pagination: { el: ".swiper-pagination", clickable: true },
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
    });

    // 2. Initialize Video Slider
    var videoSwiper = new Swiper(".videoSwiper", {
        slidesPerView: 2.1,
        spaceBetween: 10,
        grabCursor: true,
        autoHeight: true,
        pagination: { el: ".videoSwiper .swiper-pagination", clickable: true },
        breakpoints: { 640: { slidesPerView: 2.2 }, 1024: { slidesPerView: 4.2 } },
    });

    function playVideo(id) {
        const player = document.getElementById('videoPlayer');
        player.src = "https://www.youtube.com/embed/" + id + "?autoplay=1";
        document.getElementById('videoModal').style.display = "flex";
        document.body.style.overflow = "hidden";
    }

    function closeVideo() {
        document.getElementById('videoPlayer').src = "";
        document.getElementById('videoModal').style.display = "none";
        document.body.style.overflow = "auto";
    }

    // Theme Toggle Logic
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
</script>
<script>
    // Weather API Integration (Open-Meteo - No API Key Required)
    async function getWeatherData() {
        try {
            // Get user location
            navigator.geolocation.getCurrentPosition(async (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                
                // Fetch weather from Open-Meteo
                const response = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&timezone=auto`);
                const data = await response.json();
                
                // Fetch City Name using Reverse Geocoding (BigDataCloud - No Key needed for basic)
                const geoResponse = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=te`);
                const geoData = await geoResponse.json();

                // Update UI
                document.getElementById('temp-display').innerText = Math.round(data.current_weather.temperature) + '°C';
                document.getElementById('city-display').innerHTML = `<i class="bi bi-geo-alt-fill text-danger me-1"></i> ${geoData.city || geoData.locality}`;
                document.getElementById('wind-display').innerText = data.current_weather.windspeed + ' km/h';
                document.getElementById('humidity-display').innerText = '65%'; // Open-meteo current lacks humidity in basic, static average for now or add to API call params
                
                // Update Weather Desc based on code
                const weatherCode = data.current_weather.weathercode;
                let desc = "ఆకాశం నిర్మలంగా ఉంది";
                let icon = '<i class="bi bi-sun-fill"></i>';
                
                if(weatherCode > 0 && weatherCode < 45) { desc = "ఆకాశం పాక్షికంగా మేఘావృతమై ఉంది"; icon = '<i class="bi bi-cloud-sun-fill"></i>'; }
                else if(weatherCode >= 45) { desc = "వర్షం పడే అవకాశం ఉంది"; icon = '<i class="bi bi-cloud-rain-fill"></i>'; }
                
                document.getElementById('weather-desc').innerText = desc;
                document.getElementById('weather-icon-div').innerHTML = icon;

            }, (error) => {
                // Fallback to Hyderabad if Location Blocked
                document.getElementById('city-display').innerText = "హైదరాబాద్ (Default)";
                document.getElementById('weather-desc').innerText = "Location access denied";
            });
        } catch (err) {
            console.error("Weather load error", err);
        }
    }
    
    // Call weather on load
    document.addEventListener('DOMContentLoaded', getWeatherData);
</script>

<?= view('frontend/includes/footer') ?>