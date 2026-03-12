<?= view('admin/includes/header', ['title' => 'Dashboard Overview']) ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    /* Premium Variables */
    :root {
        --slate-900: #0f172a;
        --slate-800: #1e293b;
        --slate-700: #334155;
        --ntoday-red: #ef4444;
    }

    /* Dashboard Specific Enhancements */
    .stat-card { 
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
        border-radius: 1.5rem; 
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.03) !important;
    }
    .stat-card:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 20px 40px rgba(0,0,0,0.06) !important; 
    }
    
    .icon-box { 
        width: 54px; 
        height: 54px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 1.1rem;
        transition: 0.3s; 
    }
    .stat-card:hover .icon-box { transform: rotate(-10deg) scale(1.1); }
    
    .glass-header {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .tracking-wider { letter-spacing: 0.08em; }
    .fw-black { font-weight: 900; }
    
    /* Custom Scrollbar for Table */
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    .border-end-md { border-right: 1px solid #f1f5f9; }
    
    /* Fancy Badge */
    .badge-soft { font-weight: 700; font-size: 0.7rem; padding: 0.5em 1.2em; border-radius: 50px; }

    /* Mobile Optimization */
    @media (max-width: 768px) { 
        .border-end-md { border-right: none; border-bottom: 1px solid #f1f5f9; } 
        .display-mobile-none { display: none; }
        .article-title-limit { max-width: 180px; }
    }
</style>

<div class="row g-3 g-md-4 mb-5 animate__animated animate__fadeIn">
    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 shadow-sm h-100 p-2 p-md-3">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success badge-soft display-mobile-none">ACTIVE</span>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase tracking-wider">Active Reporters</p>
                <h2 class="fw-black mb-0 text-slate-900"><?= $active_reporters_count ?? '0' ?></h2>
                <div class="mt-3 extra-small">
                    <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Verified Network</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 shadow-sm h-100 p-2 p-md-3">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="bi bi-newspaper fs-4"></i>
                    </div>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase tracking-wider">Total News</p>
                <h2 class="fw-black mb-0 text-slate-900"><?= $total_news_count ?? '0' ?></h2>
                <div class="mt-3 small text-muted">
                    <i class="bi bi-calendar-check me-1"></i> Lifetime Records
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 shadow-sm h-100 p-2 p-md-3" style="border-top: 4px solid var(--ntoday-red) !important;">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-lightning-charge-fill fs-4"></i>
                    </div>
                    <div class="spinner-grow spinner-grow-sm text-danger animate-pulse" role="status"></div>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase tracking-wider">Today's News</p>
                <h2 class="fw-black mb-0 text-danger"><?= $todays_news_count ?? '0' ?></h2>
                <div class="mt-3 small text-danger fw-bold">
                    <i class="bi bi-dot animate__animated animate__flash animate__infinite"></i> Live Updates
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 shadow-sm h-100 p-2 p-md-3">
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="bi bi-eye-fill fs-4"></i>
                    </div>
                </div>
                <p class="text-muted small fw-bold mb-1 text-uppercase tracking-wider">Portal Views</p>
                <h2 class="fw-black mb-0 text-slate-900"><?= number_format($total_views ?? 0) ?></h2>
                <div class="mt-3 small text-info fw-bold">
                    <i class="bi bi-arrow-up-right me-1"></i> Global Traffic
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden animate__animated animate__fadeInLeft">
            <div class="card-header bg-danger text-white border-0 py-3 d-flex align-items-center">
                <i class="bi bi-shield-lock-fill me-2"></i>
                <h6 class="fw-bold mb-0">Action Required</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="<?= base_url('admin/news/pending') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="bi bi-file-earmark-post text-warning"></i>
                            </div>
                            <span class="fw-semibold small">News Approvals</span>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill"><?= $pending_news_count ?? 0 ?></span>
                    </a>
                    <a href="<?= base_url('admin/reporters/pending') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="bi bi-person-plus-fill text-primary"></i>
                            </div>
                            <span class="fw-semibold small">Reporter Requests</span>
                        </div>
                        <span class="badge bg-primary rounded-pill"><?= $pending_reporters_count ?? 0 ?></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-primary text-white border-0 py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-collection-play me-2"></i>Banner Module</h6>
            </div>
            <div class="card-body p-3">
                <a href="<?= base_url('admin/banners') ?>" class="btn btn-outline-primary w-100 rounded-3 border-dashed d-flex align-items-center justify-content-center py-2">
                    <i class="bi bi-plus-circle-dotted me-2"></i>
                    <span class="small fw-bold">Manage & Upload Banners</span>
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-dark text-white border-0 py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Approved Reporters</h6>
            </div>
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="small text-muted fw-semibold">Database Count</span>
                    <h5 class="fw-black mb-0"><?= $active_reporters_count ?? 0 ?></h5>
                </div>
                <hr class="my-3 opacity-5">
                <a href="<?= base_url('admin/reporters') ?>" class="btn btn-dark w-100 btn-sm rounded-pill">View Full List</a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-2 animate__animated animate__fadeInRight">
            <div class="card-body">
                <div class="row align-items-center h-100">
                    <div class="col-md-5 text-center border-end-md py-4">
                        <div class="position-relative d-inline-block mb-3">
                            <?php 
                                $reporter_img = (!empty($star_reporter['profile_pic'])) ? base_url('uploads/profile_pics/'.$star_reporter['profile_pic']) : base_url('assets/images/default-user.png');
                            ?>
                            <div class="pulse-ring"></div>
                            <img src="<?= $reporter_img ?>" class="rounded-circle shadow-lg border border-4 border-warning p-1 position-relative" width="130" height="130" style="object-fit:cover; z-index:2;">
                            <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-warning p-2 border border-2 border-white shadow" style="z-index:3;">
                                <i class="bi bi-trophy-fill text-dark"></i>
                            </span>
                        </div>
                        <h5 class="fw-black mb-1 mt-2"><?= esc($star_reporter['display_name'] ?? 'No Data Yet') ?></h5>
                        <p class="text-warning fw-bold small text-uppercase tracking-wider mb-4">Weekly Star Reporter</p>
                        <div class="d-grid px-4">
                            <button class="btn btn-warning btn-sm rounded-pill fw-bold text-dark shadow-sm">View Analytics</button>
                        </div>
                    </div>
                    <div class="col-md-7 ps-md-5 py-4">
                        <h6 class="fw-bold mb-4 text-dark d-flex align-items-center">
                            <span class="bg-primary bg-opacity-10 p-2 rounded-3 me-2"><i class="bi bi-bar-chart-line-fill text-primary"></i></span>
                            Top Contributors
                        </h6>
                        <?php if(!empty($reporter_performance)): 
                            $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                            $i = 0;
                            $max_news = $reporter_performance[0]['news_count'] > 0 ? $reporter_performance[0]['news_count'] : 1;
                            foreach($reporter_performance as $perf): 
                        ?>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small fw-bold text-slate-700"><?= esc($perf['display_name']) ?></span>
                                <span class="badge bg-light text-dark fw-bold" style="font-size: 0.65rem;"><?= $perf['news_count'] ?> Posts</span>
                            </div>
                            <div class="progress rounded-pill shadow-none" style="height: 10px; background: #f1f5f9;">
                                <div class="progress-bar <?= $colors[$i % 5] ?> rounded-pill animate__animated animate__slideInLeft" role="progressbar" style="width: <?= ($perf['news_count']/$max_news)*100 ?>%"></div>
                            </div>
                        </div>
                        <?php $i++; endforeach; else: ?>
                            <div class="text-center py-4">
                                <p class="text-muted small italic">No performance data available.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 animate__animated animate__fadeInUp">
    <div class="card-header bg-white p-4 border-0">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="fw-black mb-0 text-uppercase tracking-wider text-slate-800 d-flex align-items-center">
                    <span class="bg-danger bg-opacity-10 p-2 rounded-3 me-2"><i class="bi bi-fire text-danger"></i></span>
                    Viral News of the Week
                </h5>
            </div>
            <div class="col text-end">
                <button class="btn btn-light btn-sm rounded-pill px-3 border"><i class="bi bi-download me-1"></i> Report</button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover">
            <thead class="bg-light text-muted" style="font-size: 0.7rem;">
                <tr>
                    <th class="ps-4 py-3 tracking-wider">NEWS HEADLINE</th>
                    <th class="display-mobile-none tracking-wider">CATEGORY</th>
                    <th class="display-mobile-none tracking-wider">REPORTER</th>
                    <th class="text-center tracking-wider">VIEWS</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($weekly_top_news)): foreach($weekly_top_news as $news): ?>
                <tr>
                    <td class="ps-4 py-4">
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3 display-mobile-none">
                                <?php 
                                    $news_img = (!empty($news['featured_image'])) ? base_url('uploads/news/'.$news['featured_image']) : base_url('assets/images/no-image.png');
                                ?>
                                <img src="<?= $news_img ?>" class="rounded-3 shadow-sm border" width="65" height="45" style="object-fit: cover;">
                            </div>
                            <div>
                                <a href="<?= base_url('news/'.$news['slug']) ?>" class="fw-bold text-slate-800 text-decoration-none article-title-limit text-truncate d-block" style="max-width: 320px;" target="_blank">
                                    <?= esc($news['title']) ?>
                                </a>
                                <small class="text-muted d-md-none fw-semibold">Category: <?= $news['category_name'] ?? 'General' ?></small>
                            </div>
                        </div>
                    </td>
                    <td class="display-mobile-none">
                        <span class="badge bg-secondary-subtle text-secondary badge-soft">
                            <?= strtoupper($news['category_name'] ?? 'General') ?>
                        </span>
                    </td>
                    <td class="display-mobile-none">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle me-2 text-primary opacity-50"></i>
                            <span class="small fw-bold text-slate-600"><?= esc($news['reporter_name'] ?? 'Admin') ?></span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-inline-flex align-items-center px-3 py-2 bg-primary bg-opacity-10 text-primary rounded-3 fw-black">
                             <?= number_format($news['view_count']) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-5 text-muted">No high-performing data available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Pulse ring for Star Reporter */
    .pulse-ring {
        position: absolute;
        width: 130px;
        height: 130px;
        border: 4px solid #ffc107;
        border-radius: 50%;
        animation: pulse-yellow 2s infinite;
        z-index: 1;
    }
    @keyframes pulse-yellow {
        0% { transform: scale(0.95); opacity: 1; }
        100% { transform: scale(1.2); opacity: 0; }
    }
</style>

<?= view('admin/includes/footer') ?>