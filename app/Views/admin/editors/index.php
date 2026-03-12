<?= view('admin/includes/header', ['title' => $title]) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-slate-800">ఎడిటర్ల నిర్వహణ (Manage Editors)</h4>
        <p class="text-muted small mb-0">వార్తలను సరిదిద్దే మరియు ప్రచురించే అధికారం ఉన్నవారు.</p>
    </div>
    <a href="<?= base_url('admin/editors/add') ?>" class="btn btn-dark rounded-pill px-4 shadow-sm">
        <i class="bi bi-person-plus me-2"></i> కొత్త ఎడిటర్ ని చేర్చు
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4 py-3 border-0">పేరు & ఈమెయిల్</th>
                    <th class="border-0">ఫోన్</th>
                    <th class="border-0">స్టేటస్</th>
                    <th class="border-0 text-center">చర్యలు</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($editors)): foreach($editors as $user): ?>
                <tr>
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-dark"><?= esc($user['display_name']) ?></div>
                        <div class="small text-muted"><?= esc($user['email']) ?></div>
                    </td>
                    <td><?= esc($user['phone'] ?? 'N/A') ?></td>
                    <td>
                        <span class="badge bg-success bg-opacity-10 text-success px-3">Active</span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group shadow-sm rounded-3">
                            <a href="<?= base_url('admin/editors/edit/'.$user['id']) ?>" class="btn btn-white btn-sm border"><i class="bi bi-pencil text-dark"></i></a>
                            <a href="<?= base_url('admin/editors/delete/'.$user['id']) ?>" class="btn btn-white btn-sm border" onclick="return confirm('తొలగించాలా?')"><i class="bi bi-trash text-danger"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4" class="py-5 text-center text-muted">ఎడిటర్లు ఎవరూ లేరు.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('admin/includes/footer') ?>