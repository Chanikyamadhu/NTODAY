<?= view('admin/includes/header', ['title' => $title]) ?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold text-slate-800 mb-1"><?= esc($reporter['display_name']) ?> రిపోర్ట్</h3>
            <p class="text-muted small mb-0">రిపోర్టర్ పనితీరు మరియు గణాంకాలు ఇక్కడ చూడవచ్చు.</p>
        </div>
        <a href="<?= base_url('admin/reporters/manage') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> వెనక్కి
        </a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white">
                <div class="small opacity-75 uppercase fw-bold mb-1">మొత్తం వార్తలు</div>
                <div class="h2 fw-black mb-0"><?= number_format($total_news) ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-success text-white">
                <div class="small opacity-75 uppercase fw-bold mb-1">ప్రచురించినవి</div>
                <div class="h2 fw-black mb-0"><?= number_format($published_news) ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-danger text-white">
                <div class="small opacity-75 uppercase fw-bold mb-1">మొత్తం వ్యూస్ (Total Views)</div>
                <div class="h2 fw-black mb-0"><?= number_format($total_views) ?></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h6 class="fw-bold mb-0">ఇటీవలి వార్తలు (Recent 10)</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4">శీర్షిక (Title)</th>
                        <th class="border-0">తేదీ</th>
                        <th class="border-0">వ్యూస్</th>
                        <th class="border-0 text-end px-4">స్థితి (Status)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($recent_news)): foreach($recent_news as $news): ?>
                    <tr>
                        <td class="px-4 fw-bold small text-slate-700"><?= mb_substr(esc($news['title']), 0, 60) ?>...</td>
                        <td class="small text-muted"><?= date('d M, Y', strtotime($news['created_at'])) ?></td>
                        <td><span class="badge bg-light text-dark fw-normal"><?= number_format($news['view_count']) ?></span></td>
                        <td class="text-end px-4">
                            <?php if($news['status'] == 1): ?>
                                <span class="badge bg-soft-success text-success border border-success small">Published</span>
                            <?php else: ?>
                                <span class="badge bg-soft-warning text-warning border border-warning small">Draft</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-5 text-muted small">వార్తలు ఏవీ లేవు.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('admin/includes/footer') ?>