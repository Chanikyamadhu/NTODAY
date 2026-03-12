<?= view('admin/includes/header', ['title' => 'Approved News']) ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-slate-800 text-success">ఆమోదించబడిన వార్తలు (Reporters Only)</h4>
        <p class="text-muted small mb-0">రిపోర్టర్ల నుండి వచ్చి, పోర్టల్‌లో లైవ్ లో ఉన్న వార్తల జాబితా.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4 py-3 border-0">తేదీ & వార్త వివరాలు</th>
                    <th class="border-0">కేటగిరీ</th>
                    <th class="border-0">రిపోర్టర్</th>
                    <th class="border-0 text-center">చర్యలు</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($news)): foreach($news as $item): ?>
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center">
                            <?php 
                                $img_path = 'uploads/news/' . $item['featured_image'];
                                $img_url = (file_exists(FCPATH . $img_path) && !empty($item['featured_image'])) ? base_url($img_path) : base_url('assets/images/no-image.png');
                            ?>
                            <img src="<?= $img_url ?>" class="rounded-3 me-3 border shadow-sm" width="65" height="48" style="object-fit: cover;">
                            <div style="max-width: 300px;">
                                <span class="fw-bold text-dark d-block mb-1 text-truncate" title="<?= esc($item['title']) ?>">
                                    <?= esc($item['title']) ?>
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-check me-1"></i> 
                                    Published: <?= $item['published_at'] ? date('d M, Y', strtotime($item['published_at'])) : date('d M, Y', strtotime($item['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                            <i class="bi bi-tag-fill me-1"></i><?= $item['category_name'] ?? 'General' ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-badge me-2 text-primary"></i>
                            <span class="small fw-semibold"><?= esc($item['reporter_name']) ?></span>
                        </div>
                    </td>
                    <td class="text-center px-4">
                        <div class="btn-group shadow-sm rounded-3 border">
                            <a href="<?= base_url('news/'.$item['slug']) ?>" target="_blank" class="btn btn-white btn-sm" title="View Live">
                                <i class="bi bi-box-arrow-up-right text-primary"></i>
                            </a>
                            <a href="<?= base_url('admin/news/edit/'.$item['id']) ?>" class="btn btn-white btn-sm border-start" title="Edit">
                                <i class="bi bi-pencil-square text-dark"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="4" class="py-5 text-center text-muted">
                        <i class="bi bi-clipboard-x fs-1 d-block mb-3 opacity-25"></i>
                        ఆమోదించబడిన రిపోర్టర్ వార్తలు ఏవీ లేవు.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    <?php if (isset($pager)): ?>
        <?= $pager->links('default', 'bootstrap_full') ?>
    <?php endif; ?>
</div>

<?= view('admin/includes/footer') ?>