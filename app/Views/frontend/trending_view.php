<?= view('frontend/includes/header', ['title' => $title]) ?>

<main class="container mt-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1">
                <span class="text-danger"><i class="fas fa-fire-alt me-2"></i>ట్రెండింగ్</span> వార్తలు
            </h2>
            <p class="text-muted mb-0">ప్రస్తుతం పాఠకులు అత్యధికంగా ఆసక్తి చూపుతున్న కథనాలు.</p>
        </div>
        <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <span class="badge bg-soft-danger text-danger border border-danger px-3 py-2 rounded-pill">
                Updated just now
            </span>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-9">
            <?php if(!empty($trending_news)): ?>
                <div class="trending-container">
                    <?php $rank = 1; foreach($trending_news as $news): ?>
                        <div class="card mb-4 border-0 shadow-sm overflow-hidden hover-card transition-all">
                            <div class="row g-0 align-items-center">
                                <div class="col-1 col-md-1 d-flex justify-content-center align-items-center bg-light bg-opacity-50">
                                    <span class="display-6 fw-black text-danger opacity-25 italic-rank"><?= $rank++ ?></span>
                                </div>
                                
                                <div class="col-4 col-md-3">
                                    <div class="position-relative overflow-hidden" style="height: 140px;">
                                        <?php 
                                            $list_item_img = (!empty($news['featured_image'])) 
                                                            ? base_url('uploads/news/'.$news['featured_image']) 
                                                            : base_url('assets/images/default.png'); 
                                        ?>
                                        <img src="<?= $list_item_img ?>" 
                                            class="img-fluid w-100 h-100 object-fit-cover" 
                                            alt="<?= esc($news['title']) ?>">
                                    </div>
                                </div>
                                
                                <div class="col-7 col-md-8">
                                    <div class="card-body py-3">
                                        <a href="<?= base_url('news/'.$news['slug']) ?>" class="text-decoration-none">
                                            <h5 class="card-title text-dark fw-bold mb-2 line-clamp-2 hover-red-text">
                                                <?= esc($news['title']) ?>
                                            </h5>
                                        </a>
                                        <div class="d-flex align-items-center gap-3 small text-muted">
                                            <span><i class="far fa-calendar-alt me-1 text-danger"></i> <?= date('d M, Y', strtotime($news['published_at'])) ?></span>
                                            <span><i class="far fa-eye me-1 text-primary"></i> <?= number_format($news['view_count']) ?> వ్యూస్</span>
                                            <span class="d-none d-md-inline ms-auto"><i class="fas fa-share-alt"></i> Share</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-center mt-5">
                    <?= $pager->links() ?>
                </div>

            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <p class="h5 text-muted">ప్రస్తుతం ట్రెండింగ్ వార్తలు ఏమీ లేవు.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-3">
            <div class="sticky-top" style="top: 100px;">
                <h6 class="fw-bold text-uppercase small letter-spacing-1 mb-3 border-start border-3 border-danger ps-2">ప్రకటనలు</h6>
                
                <div class="sidebar-ads-wrapper">
                    <?php if (!empty($sidebar_ads)): ?>
                        <?php 
                        // గరిష్టంగా 3 ప్రకటనలు మాత్రమే చూపించడానికి లూప్
                        $ad_count = 0;
                        foreach ($sidebar_ads as $ad): 
                            if($ad_count >= 3) break;
                        ?>
                            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden group">
                                <a href="<?= esc($ad['link_url']) ?>" target="_blank" class="text-decoration-none">
                                    <div class="position-relative">
                                        <img src="<?= base_url('uploads/banners/' . $ad['image_path']) ?>" 
                                            class="img-fluid w-100 transition-all" 
                                            alt="<?= esc($ad['title']) ?>"
                                            style="object-fit: cover; min-height: 200px;">
                                        
                                        <span class="position-absolute top-0 end-0 badge bg-dark opacity-75 m-2 shadow-sm" style="font-size: 10px;">Sponsored</span>
                                    </div>
                                    <?php if(!empty($ad['title'])): ?>
                                        <div class="p-2 bg-white border-top">
                                            <h6 class="small fw-bold text-dark mb-0 text-truncate text-center"><?= esc($ad['title']) ?></h6>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php 
                            $ad_count++;
                        endforeach; 
                        ?>
                        
                        <?php for($i = $ad_count; $i < 3; $i++): ?>
                            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                                <div class="bg-soft-danger text-center py-5 border-dashed rounded-4 m-2">
                                    <i class="fas fa-ad fa-2x text-danger opacity-25"></i>
                                    <p class="small text-muted mt-2 mb-0">మీ ప్రకటన కోసం<br>మమ్మల్ని సంప్రదించండి</p>
                                    <a href="<?= base_url('contact-us') ?>" class="btn btn-sm btn-danger rounded-pill mt-2 px-3 fw-bold">Contact Us</a>
                                </div>
                            </div>
                        <?php endfor; ?>

                    <?php else: ?>
                        <?php for($i = 0; $i < 3; $i++): ?>
                            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                                <div class="bg-soft-danger text-center py-5 border-dashed rounded-4 m-2">
                                    <i class="fas fa-ad fa-2x text-danger opacity-25"></i>
                                    <p class="small text-muted mt-2 mb-0">మీ ప్రకటన కోసం<br>మమ్మల్ని సంప్రదించండి</p>
                                    <a href="<?= base_url('contact-us') ?>" class="btn btn-sm btn-danger rounded-pill mt-2 px-3 fw-bold">Contact Us</a>
                                </div>
                            </div>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>

                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white p-3 mb-4">
                    <h6 class="fw-bold small mb-2">Follow NToday</h6>
                    <p class="x-small opacity-75">వార్తలను ఎప్పటికప్పుడు వాట్సాప్‌లో పొందండి.</p>
                    <a href="#" class="btn btn-danger btn-sm rounded-pill fw-bold">Join Now</a>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Custom Styles */
    .hover-card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        background-color: #fff9f9;
    }
    .hover-red-text:hover {
        color: #dc3545 !important;
    }
    .italic-rank {
        font-style: italic;
        font-family: 'Arial Black', sans-serif;
    }
    .bg-soft-danger {
        background-color: rgba(220, 53, 69, 0.05);
    }
    .letter-spacing-1 {
        letter-spacing: 1px;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .x-small {
        font-size: 0.75rem;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>

<?= view('frontend/includes/footer') ?>