<?= view('admin/includes/header', ['title' => $title]) ?>

<div class="container py-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-slate-800">రిపోర్టర్ల నిర్వహణ (Manage Reporters)</h4>
            <p class="text-muted small mb-0">మీ పోర్టల్‌లో పనిచేస్తున్న రిపోర్టర్ల వివరాలు మరియు వారి పనితీరు ఇక్కడ చూడవచ్చు.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 border-0">రిపోర్టర్ వివరాలు</th>
                        <th class="border-0">స్టేటస్</th>
                        <th class="border-0">మొత్తం వార్తలు</th>
                        <th class="border-0">జాయిన్ అయిన తేదీ</th>
                        <th class="border-0 text-center">చర్యలు</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($reporters)): foreach($reporters as $user): ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="reporter-avatar me-3 fw-bold shadow-sm">
                                    <?= strtoupper(substr(esc($user['display_name']), 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark text-capitalize"><?= esc($user['display_name']) ?></div>
                                    <div class="small text-muted font-monospace"><?= esc($user['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if($user['status'] == 1): ?>
                                <span class="badge bg-soft-success text-success border border-success rounded-pill px-3">Active</span>
                            <?php elseif($user['status'] == 2): ?>
                                <span class="badge bg-soft-danger text-danger border border-danger rounded-pill px-3">Inactive</span>
                            <?php else: ?>
                                <span class="badge bg-soft-warning text-warning border border-warning rounded-pill px-3">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-slate-700">
                                <i class="bi bi-file-earmark-text me-1 text-muted"></i>
                                <?= number_format($user['total_news'] ?? 0) ?> 
                                <span class="small fw-normal text-muted">వార్తలు</span>
                            </div>
                        </td>
                        <td class="text-secondary small font-monospace">
                            <?= date('d M, Y', strtotime($user['created_at'])) ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm rounded-3 bg-white border">
                                <a href="<?= base_url('admin/reporters/edit/'.$user['id']) ?>" class="btn btn-white btn-sm px-3" title="Edit Profile">
                                    <i class="bi bi-pencil-square text-dark"></i>
                                </a>
                                
                                <a href="<?= base_url('admin/reporters/stats/'.$user['id']) ?>" class="btn btn-white btn-sm px-3 border-start border-end" title="View Stats">
                                    <i class="bi bi-graph-up text-primary"></i>
                                </a>

                                <?php if($user['status'] == 1): ?>
                                    <a href="<?= base_url('admin/reporters/deactivate/'.$user['id']) ?>" 
                                       class="btn btn-white btn-sm px-3" 
                                       title="Deactivate" 
                                       onclick="return confirm('ఈ రిపోర్టర్‌ను డీయాక్టివేట్ చేయాలనుకుంటున్నారా?')">
                                        <i class="bi bi-person-x text-danger"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/reporters/activate/'.$user['id']) ?>" 
                                       class="btn btn-white btn-sm px-3" 
                                       title="Activate">
                                        <i class="bi bi-person-check text-success"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="py-5 text-center text-muted">
                            <div class="py-4">
                                <i class="bi bi-people fs-1 d-block mb-3 opacity-25"></i>
                                <h6 class="fw-bold">రిపోర్టర్లు ఎవరూ లేరు.</h6>
                                <p class="small">ప్రస్తుతం మీ డేటాబేస్‌లో రిపోర్టర్ల వివరాలు ఏవీ కనిపించడం లేదు.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Premium UI Enhancements */
    .text-slate-800 { color: #1e293b; }
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.12); }
    .bg-soft-danger { background-color: rgba(220, 53, 69, 0.12); }
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.12); }
    
    .reporter-avatar {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(248, 250, 252, 0.8);
        transition: background 0.2s ease;
    }

    .btn-white {
        background-color: #fff;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-white:hover {
        background-color: #f1f5f9;
        transform: translateY(-1px);
    }

    .font-monospace { font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important; }
</style>

<?= view('admin/includes/footer') ?>