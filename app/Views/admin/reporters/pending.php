<?= view('admin/includes/header', ['title' => $title]) ?>

<div class="container-fluid py-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h4 class="fw-bold text-slate-800 mb-1">కొత్త రిపోర్టర్ల దరఖాస్తులు</h4>
            <p class="text-muted small mb-0">
                <span class="badge bg-soft-warning text-warning fw-bold px-2 py-1 me-1">Pending</span> 
                వీరిని ఆమోదించిన తర్వాతే వారు వార్తలు రాయగలరు.
            </p>
        </div>
        <div class="text-end">
            <span class="text-slate-700 small fw-bold">మొత్తం దరఖాస్తులు: <?= count($reporters ?? []) ?></span>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3 border-0">రిపోర్టర్ వివరాలు</th>
                        <th class="border-0">పూర్తి పేరు (Full Name)</th>
                        <th class="border-0">సంప్రదించు (Contact)</th>
                        <th class="border-0">దరఖాస్తు తేదీ</th>
                        <th class="border-0 text-center">చర్యలు (Actions)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($reporters)): foreach($reporters as $user): ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <?php 
                                    $img_path = 'uploads/profile/'.$user['profile_pic'];
                                    $display_img = (!empty($user['profile_pic']) && file_exists(FCPATH . $img_path)) ? base_url($img_path) : null;
                                ?>
                                
                                <?php if($display_img): ?>
                                    <img src="<?= $display_img ?>" class="avatar-circle me-3 shadow-sm object-fit-cover border" alt="Profile">
                                <?php else: ?>
                                    <div class="avatar-circle me-3 fw-bold text-white bg-indigo shadow-sm">
                                        <?= strtoupper(substr(esc($user['username']), 0, 1)) ?>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <div class="fw-bold text-dark text-capitalize"><?= esc($user['username']) ?></div>
                                    <small class="text-muted font-monospace">Ref ID: #<?= $user['id'] ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-slate-700 fw-medium"><?= esc($user['full_name'] ?? 'Not Provided') ?></div>
                        </td>
                        <td>
                            <div class="small fw-bold text-slate-700">
                                <i class="bi bi-envelope-at me-1 text-muted"></i> <?= esc($user['email']) ?>
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-telephone me-1 text-muted"></i> <?= esc($user['phone'] ?? 'N/A') ?>
                            </div>
                        </td>
                        <td>
                            <div class="small text-slate-600">
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                <?= date('d M, Y', strtotime($user['created_at'])) ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="<?= base_url('admin/reporters/edit/'.$user['id']) ?>" 
                                   class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm border-2" 
                                   title="View & Edit Full Profile">
                                    <i class="bi bi-person-badge me-1"></i> View
                                </a>

                                <div class="btn-group shadow-sm rounded-pill p-1 bg-white border">
                                    <a href="<?= base_url('admin/reporters/approve/'.$user['id']) ?>" 
                                       class="btn btn-success btn-sm rounded-pill px-3 fw-bold" 
                                       title="Approve">
                                        <i class="bi bi-check-lg me-1"></i> Approve
                                    </a>
                                    <a href="<?= base_url('admin/reporters/reject/'.$user['id']) ?>" 
                                       class="btn btn-outline-danger btn-sm rounded-pill px-3 ms-1 fw-bold border-0" 
                                       onclick="return confirm('ఈ దరఖాస్తును తిరస్కరించాలా?')" 
                                       title="Reject">
                                        <i class="bi bi-x-lg me-1"></i> Reject
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="py-5 text-center text-muted">
                            <div class="py-4">
                                <i class="bi bi-clipboard-x fs-1 opacity-25 d-block mb-3"></i>
                                <h6 class="fw-bold text-slate-400">ప్రస్తుతం ఎటువంటి దరఖాస్తులు లేవు.</h6>
                                <p class="small mb-0">కొత్త రిపోర్టర్లు సైన్-అప్ అయినప్పుడు ఇక్కడ కనిపిస్తారు.</p>
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
    .bg-indigo { background-color: #6366f1; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-400 { color: #94a3b8; }
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.15); }
    
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        object-fit: cover;
    }

    .font-monospace {
        font-family: 'SFMono-Regular', Consolas, "Liberation Mono", Menlo, monospace !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(248, 250, 252, 0.8);
        transition: background 0.2s ease;
    }

    .btn-group .btn {
        transition: all 0.2s ease;
    }
</style>

<?= view('admin/includes/footer') ?>