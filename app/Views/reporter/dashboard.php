<?= view('reporter/includes/header', ['title' => 'NToday - రిపోర్టర్ డ్యాష్‌బోర్డ్']) ?>

<style>
    :root { --ntoday-red: #dc3545; }
    /* Stats Card Enhancements */
    .stats-card { border: none; border-radius: 16px; transition: 0.3s; border-bottom: 5px solid transparent; overflow: hidden; }
    .stats-card:hover { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(0,0,0,0.08) !important; }
    .card-published { border-color: #28a745; background: linear-gradient(135deg, #ffffff 0%, #f0fff4 100%); }
    .card-pending { border-color: #ffc107; background: linear-gradient(135deg, #ffffff 0%, #fffdf0 100%); }
    
    /* UI Components */
    .news-table-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
    .status-badge { font-size: 0.7rem; padding: 6px 14px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px; }
    .btn-action { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; margin: 0 3px; transition: 0.2s; text-decoration: none; }
    .btn-action:hover { transform: scale(1.1); }
    
    @media (max-width: 768px) {
        .display-mobile-none { display: none; }
    }
</style>

<div class="container pb-5">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-5">
        <div class="col-6 col-md-4">
            <div class="card stats-card card-published p-3 shadow-sm h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-2 p-md-3 rounded-circle me-2 me-md-3">
                        <i class="fas fa-check-double text-success fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small fw-bold display-mobile-none">లైవ్ వార్తలు</h6>
                        <h6 class="text-muted mb-0 small fw-bold d-md-none">లైవ్</h6>
                        <h3 class="mb-0 fw-bold"><?= $published_count ?? 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stats-card card-pending p-3 shadow-sm h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-2 p-md-3 rounded-circle me-2 me-md-3">
                        <i class="fas fa-hourglass-half text-warning fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0 small fw-bold display-mobile-none">పెండింగ్ వార్తలు</h6>
                        <h6 class="text-muted mb-0 small fw-bold d-md-none">పెండింగ్</h6>
                        <h3 class="mb-0 fw-bold"><?= $pending_count ?? 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <a href="<?= base_url('reporter/add-news') ?>" class="btn btn-danger btn-lg rounded-4 shadow-sm w-100 h-100 d-flex align-items-center justify-content-center">
                <i class="fas fa-pen-nib me-2"></i> కొత్త వార్త రాయండి
            </a>
        </div>
    </div>

    <div class="card news-table-card shadow-sm">
        <div class="card-body p-0">
            <div class="p-4 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-list-ul me-2 text-danger"></i>నా వార్తలు</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-muted text-uppercase">
                            <th class="ps-4 py-3">న్యూస్</th>
                            <th class="display-mobile-none">తేదీ</th>
                            <th>స్టేటస్</th>
                            <th class="text-center pe-4">చర్యలు</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($my_news)): foreach($my_news as $news): ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <img src="<?= base_url('uploads/news/'.$news['featured_image']) ?>" 
                                         class="rounded-3 shadow-sm me-3" style="width: 55px; height: 55px; object-fit: cover;"
                                         onerror="this.src='<?= base_url('assets/images/default-news.jpg') ?>'">
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">
                                            <?= esc($news['title']) ?>
                                        </div>
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-eye me-1"></i> <?= number_format($news['view_count'] ?? 0) ?> వ్యూస్ 
                                            <span class="d-md-none ms-2">| <?= date('d M', strtotime($news['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="small text-muted display-mobile-none">
                                <?= date('d M, Y', strtotime($news['created_at'])) ?>
                            </td>
                            <td>
                                <?php if($news['status'] == 1 || $news['status'] == 'published'): ?>
                                    <span class="status-badge bg-success bg-opacity-10 text-success fw-bold">
                                        <i class="fas fa-check-circle me-1"></i> Live
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge bg-warning bg-opacity-10 text-warning fw-bold">
                                        <i class="fas fa-clock me-1"></i> Review
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center">
                                    <a href="<?= base_url('news/'.$news['slug']) ?>" target="_blank" 
                                       class="btn-action bg-primary bg-opacity-10 text-primary" title="View">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="<?= base_url('reporter/edit/'.$news['id']) ?>" 
                                       class="btn-action bg-info bg-opacity-10 text-info" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="<?= base_url('reporter/delete/'.$news['id']) ?>" 
                                       onclick="return confirm('నిజంగా తొలగించాలనుకుంటున్నారా?')"
                                       class="btn-action bg-danger bg-opacity-10 text-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fas fa-newspaper mb-3 opacity-25 fs-1"></i>
                                <p class="text-muted">మీరు ఇంకా ఎటువంటి వార్తలు రాయలేదు.</p>
                                <a href="<?= base_url('reporter/add-news') ?>" class="btn btn-danger btn-sm rounded-pill px-4">మొదటి వార్త రాయండి</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= view('reporter/includes/footer') ?>