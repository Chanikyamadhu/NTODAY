<?= view('admin/includes/header', ['title' => 'Pending News Approvals']) ?>

<style>
    .news-title-link { color: #1e293b; text-decoration: none; transition: 0.2s; }
    .news-title-link:hover { color: #d32f2f; }
    .table-hover tbody tr:hover { background-color: #f8fafc; transition: 0.3s; }
    .btn-action-group .btn { 
        border: none; 
        background: #fff; 
        padding: 8px 12px; 
        transition: all 0.2s; 
    }
    .btn-action-group .btn:hover { background: #f1f5f9; transform: translateY(-1px); }
    .avatar-init {
        width: 38px; height: 38px; 
        font-size: 14px; font-weight: 700;
        background: linear-gradient(135deg, #6366f1, #4338ca);
        color: white; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .badge-cat {
        background: rgba(99, 102, 241, 0.1);
        color: #4f46e5;
        font-weight: 600;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }
    .img-thumbnail-custom {
        width: 75px; height: 52px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-slate-800 te-font">ఆమోదం కోసం వేచి ఉన్న వార్తలు</h3>
            <p class="text-muted mb-0"><i class="bi bi-info-circle me-1"></i> రిపోర్టర్ల నుండి వచ్చిన కొత్త వార్తలను ఇక్కడ రివ్యూ చేసి ప్రచురించవచ్చు.</p>
        </div>
        <div>
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10 px-4 py-2 rounded-4 border-start border-4 border-warning">
                <span class="text-warning-emphasis small fw-bold text-uppercase d-block" style="letter-spacing: 1px;">Pending Items</span>
                <h4 class="mb-0 fw-black"><?= count($news) ?></h4>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 border-0 text-muted small text-uppercase fw-bold">తేదీ & వార్త వివరాలు</th>
                        <th class="border-0 text-muted small text-uppercase fw-bold">విభాగం (Category)</th>
                        <th class="border-0 text-muted small text-uppercase fw-bold">రిపోర్టర్</th>
                        <th class="border-0 text-center text-muted small text-uppercase fw-bold">చర్యలు (Actions)</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if(!empty($news)): foreach($news as $item): ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <?php 
                                    $img_path = 'uploads/news/' . $item['featured_image'];
                                    $img_url = (file_exists(FCPATH . $img_path) && !empty($item['featured_image'])) ? base_url($img_path) : base_url('assets/images/no-image.png');
                                ?>
                                <div class="position-relative">
                                    <img src="<?= $img_url ?>" class="img-thumbnail-custom border me-3">
                                </div>
                                
                                <div style="max-width: 400px;">
                                    <a href="<?= base_url('news/view/'.$item['slug']) ?>" target="_blank" class="news-title-link fw-bold d-block mb-1 fs-6">
                                        <?= esc($item['title']) ?>
                                    </a>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted extra-small d-flex align-items-center" style="font-size: 12px;">
                                            <i class="bi bi-calendar3 me-1"></i> <?= date('d M, Y', strtotime($item['created_at'])) ?>
                                        </span>
                                        <span class="text-muted extra-small">•</span>
                                        <span class="text-muted extra-small" style="font-size: 12px;">
                                            <i class="bi bi-clock me-1"></i> <?= date('H:i A', strtotime($item['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-cat rounded-pill px-3 py-2">
                                <i class="bi bi-tag-fill me-1 small"></i> <?= $item['category_name'] ?? 'General' ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-init me-2">
                                    <?= strtoupper(substr($item['reporter_name'] ?? 'R', 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold small text-dark" style="line-height: 1.2;"><?= $item['reporter_name'] ?? 'Reporter' ?></p>
                                    <p class="mb-0 text-muted extra-small" style="font-size: 11px;">ID: #REP-<?= $item['author_id'] ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center px-4">
                            <div class="btn-group btn-action-group shadow-sm rounded-3 overflow-hidden border">
                                <a href="<?= base_url('news/view/'.$item['slug']) ?>" target="_blank" class="btn border-end" title="View Story">
                                    <i class="bi bi-eye-fill text-info"></i>
                                </a>
                                <a href="<?= base_url('admin/news/approve/'.$item['id']) ?>" class="btn border-end" title="Approve">
                                    <i class="bi bi-check-lg text-success fw-bold"></i>
                                </a>
                                <a href="<?= base_url('admin/news/reject/'.$item['id']) ?>" class="btn" onclick="return confirm('ఈ వార్తను ఖచ్చితంగా తిరస్కరించాలా?')" title="Reject">
                                    <i class="bi bi-trash3-fill text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" class="py-5 text-center">
                            <div class="py-4">
                                <div class="mb-3">
                                    <i class="bi bi-clipboard-check text-muted opacity-25" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-dark fw-bold te-font">ప్రస్తుతం ఎటువంటి పెండింగ్ వార్తలు లేవు.</h5>
                                <p class="text-muted mx-auto" style="max-width: 300px;">అన్ని వార్తలు రివ్యూ చేయబడ్డాయి. కొత్త వార్తలు రాగానే ఇక్కడ కనిపిస్తాయి.</p>
                                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary rounded-pill px-4 mt-2">Back to Dashboard</a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('admin/includes/footer') ?>