<?= view('admin/includes/header', ['title' => 'Manage News - NToday']) ?>

<style>
    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-800: #1e293b;
        --primary-indigo: #4f46e5;
    }
    .te-font { font-family: 'Ramabhadra', 'Inter', sans-serif; }
    .news-card-img {
        width: 70px; height: 50px;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }
    tr:hover .news-card-img { transform: scale(1.1); }
    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
    }
    .btn-action {
        width: 32px; height: 32px;
        display: inline-flex;
        align-items: center; justify-content: center;
        transition: all 0.2s;
        border-radius: 8px !important;
    }
    .filter-card {
        background: linear-gradient(to right, #ffffff, var(--slate-50));
        border: 1px solid #e2e8f0;
    }
    .table thead th {
        background-color: var(--slate-50);
        letter-spacing: 0.05em;
        color: #64748b !important;
    }
    .pagination-container .pagination { gap: 5px; }
    .pagination-container .page-link {
        border-radius: 8px;
        color: var(--slate-800);
        border: 1px solid #e2e8f0;
    }
    .pagination-container .active .page-link {
        background-color: var(--slate-800);
        border-color: var(--slate-800);
    }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 animate__animated animate__fadeIn">
    <div>
        <h3 class="fw-bold mb-1 text-slate-800 te-font">అన్ని వార్తలు (All News)</h3>
        <p class="text-muted mb-0"><i class="bi bi-collection-play me-1"></i> పోర్టల్‌లో ప్రచురించబడిన మొత్తం వార్తల జాబితా ఇక్కడ చూడవచ్చు.</p>
    </div>
    <a href="<?= base_url('admin/news/add') ?>" class="btn btn-primary rounded-pill px-4 shadow-lg py-2">
        <i class="bi bi-plus-circle-fill me-2"></i> కొత్త వార్తను చేర్చు
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4 filter-card">
    <div class="card-body p-4">
        <form action="<?= base_url('admin/news') ?>" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">సెర్చ్ (Search Title)</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="<?= esc($search_term ?? '') ?>" class="form-control border-start-0 ps-0 shadow-none" placeholder="వార్త శీర్షికతో వెతకండి...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">విభాగం (Category)</label>
                <select name="category" class="form-select border-slate-200 shadow-none">
                    <option value="">అన్ని కేటగిరీలు</option>
                    <?php if(!empty($categories)): foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($selected_cat) && $selected_cat == $cat['id']) ? 'selected' : '' ?>>
                            <?= $cat['name'] ?>
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">స్థితి (Status)</label>
                <select name="status" class="form-select border-slate-200 shadow-none">
                    <option value="">అన్ని (Any Status)</option>
                    <option value="1" <?= (isset($selected_status) && $selected_status === '1') ? 'selected' : '' ?>>Published</option>
                    <option value="0" <?= (isset($selected_status) && $selected_status === '0') ? 'selected' : '' ?>>Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold py-2">Filter</button>
                    <a href="<?= base_url('admin/news') ?>" class="btn btn-outline-secondary py-2" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden animate__animated animate__fadeInUp">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="small text-uppercase fw-bold">
                <tr>
                    <th class="ps-4 py-3 border-0">వార్త వివరాలు</th>
                    <th class="border-0">కేటగిరీ</th>
                    <th class="border-0">రచయిత</th>
                    <th class="border-0">స్టేటస్</th>
                    <th class="border-0 text-center">చర్యలు</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($news_list)): foreach($news_list as $news): ?>
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center">
                            <?php 
                                $img_path = 'uploads/news/' . $news['featured_image'];
                                $img_url = (file_exists(FCPATH . $img_path) && !empty($news['featured_image'])) ? base_url($img_path) : base_url('assets/images/no-image.png');
                            ?>
                            <div class="position-relative">
                                <img src="<?= $img_url ?>" class="news-card-img border shadow-sm" alt="Thumbnail">
                            </div>
                            <div class="ms-3" style="max-width: 320px;">
                                <a href="<?= base_url('news/'.$news['slug']) ?>" target="_blank" class="fw-bold text-slate-800 d-block mb-1 text-decoration-none text-truncate" title="<?= esc($news['title']) ?>">
                                    <?= esc($news['title']) ?>
                                </a>
                                <div class="d-flex align-items-center gap-2 small text-muted">
                                    <span><i class="bi bi-calendar3 me-1"></i> <?= date('d M, Y', strtotime($news['created_at'])) ?></span>
                                    <span>•</span>
                                    <span><i class="bi bi-eye me-1"></i> <?= number_format($news['view_count'] ?? 0) ?></span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(79, 70, 229, 0.1); color: var(--primary-indigo);">
                            <i class="bi bi-tag-fill me-1 small"></i><?= $news['category_name'] ?? 'General' ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-1 me-2">
                                <i class="bi bi-person-circle text-primary fs-5"></i>
                            </div>
                            <div>
                                <div class="small fw-bold text-dark lh-1 mb-1">
                                    <?= esc($news['full_name'] ?? 'Admin') ?>
                                </div>
                                <div class="text-muted" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="bi bi-shield-check me-1"></i><?= esc($news['role'] ?? 'Super Admin') ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if($news['status'] == 1): ?>
                            <span class="status-badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                <i class="bi bi-check-circle-fill me-1"></i>Published
                            </span>
                        <?php else: ?>
                            <span class="status-badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                <i class="bi bi-hourglass-split me-1"></i>Pending
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center px-4">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="<?= base_url('news/'.$news['slug']) ?>" target="_blank" class="btn btn-action btn-outline-primary border-0" title="View"><i class="bi bi-eye-fill"></i></a>
                            <a href="<?= base_url('admin/news/edit/'.$news['id']) ?>" class="btn btn-action btn-outline-dark border-0" title="Edit"><i class="bi bi-pencil-square"></i></a>
                            <a href="<?= base_url('admin/news/delete/'.$news['id']) ?>" class="btn btn-action btn-outline-danger border-0" title="Delete" onclick="return confirm('ఈ వార్తను ఖచ్చితంగా తొలగించాలా?')"><i class="bi bi-trash3-fill"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" class="py-5 text-center">
                        <div class="opacity-25 mb-3"><i class="bi bi-journal-x" style="font-size: 4rem;"></i></div>
                        <h5 class="text-secondary fw-bold te-font">ఎటువంటి వార్తలు లభించలేదు.</h5>
                        <p class="text-muted">మీరు వెతికిన ఫిల్టర్లకు సరిపోయే డేటా ఏమీ లేదు.</p>
                        <a href="<?= base_url('admin/news') ?>" class="btn btn-sm btn-dark rounded-pill px-3">Reset Filters</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center animate__animated animate__fadeInUp">
    <?php if (isset($pager) && $pager): ?>
        <nav class="pagination-container">
            <?= $pager->links('default', 'bootstrap_full') ?>
        </nav>
    <?php endif; ?>
</div>

<?= view('admin/includes/footer') ?>