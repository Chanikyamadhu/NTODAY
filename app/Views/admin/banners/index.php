<?= view('admin/includes/header', ['title' => 'Manage Banners']) ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-1">ప్రకటనల నిర్వహణ (Banners)</h3>
            <p class="text-muted small">వెబ్‌సైట్ హోమ్‌పేజీ మరియు ఇతర విభాగాల్లో కనిపించే బ్యానర్లను ఇక్కడ నిర్వహించండి.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-primary d-flex align-items-center">
                        <span class="bg-primary-subtle p-2 rounded-3 me-2">
                            <i class="bi bi-plus-circle-fill text-primary"></i>
                        </span>
                        కొత్త బ్యానర్ జోడించండి
                    </h5>
                    
                    <form action="<?= base_url('admin/banners/store') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">బ్యానర్ టైటిల్</label>
                            <input type="text" name="title" class="form-control border-light-subtle rounded-3 py-2" placeholder="ఉదా: సంక్రాంతి ఆఫర్" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">బ్యానర్ పొజిషన్</label>
                            <select name="position" id="bannerPosition" class="form-select border-light-subtle rounded-3 py-2" onchange="updateSizeGuide()" required>
                                <option value="top_banner" data-size="1320 x 180">Top Banner (హెడర్ కింద)</option>
                                <option value="slide_ad" data-size="1200 x 675">Slider Ad (వార్తల మధ్య)</option>
                                <option value="sidebar_ad" data-size="350 x 600">Sidebar Ad (సైడ్ బార్)</option>
                                <option value="category_ad" data-size="1100 x 150">Category Ad (విభాగాల మధ్య)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">చిన్న వివరణ (Summary)</label>
                            <textarea name="summary" class="form-control border-light-subtle rounded-3" rows="2" placeholder="బ్యానర్ గురించి క్లుప్తంగా..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">లింక్ URL (Target Link)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light-subtle"><i class="bi bi-link-45deg"></i></span>
                                <input type="url" name="link_url" class="form-control border-light-subtle rounded-end-3 py-2" placeholder="https://example.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">బ్యానర్ ఇమేజ్ ఎంచుకోండి</label>
                            <div class="upload-area border border-2 border-dashed rounded-4 p-3 text-center bg-light-subtle position-relative">
                                <i class="bi bi-image fs-1 text-muted opacity-50"></i>
                                <p class="small text-muted mb-2 mt-1">క్లిక్ చేయండి లేదా ఇమేజ్ డ్రాప్ చేయండి</p>
                                <input type="file" name="banner_image" class="form-control stretched-link opacity-0" accept="image/*" id="bannerInput" required>
                                <img id="bannerPreview" class="img-fluid rounded-3 d-none shadow-sm mt-2" alt="Preview">
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small id="sizeGuide" class="text-primary fw-semibold" style="font-size: 11px;">Recommended: 1320 x 180 px</small>
                                <small class="text-muted" style="font-size: 11px;">Format: JPG, PNG, WebP</small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> అప్‌లోడ్ చేసి పబ్లిష్ చేయి
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-dark">అందుబాటులో ఉన్న బ్యానర్లు</h6>
                    <span class="badge bg-primary-subtle text-primary rounded-pill"><?= count($banners) ?> Active</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light-subtle border-bottom border-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-bold" style="font-size: 11px; width: 180px;">PREVIEW</th>
                                <th class="py-3 text-muted fw-bold" style="font-size: 11px;">DETAILS & POSITION</th>
                                <th class="py-3 text-muted fw-bold" style="font-size: 11px;">STATUS</th>
                                <th class="text-center py-3 text-muted fw-bold" style="font-size: 11px;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($banners)): foreach($banners as $banner): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="position-relative group overflow-hidden rounded-3 border bg-light">
                                        <img src="<?= base_url('uploads/banners/'.$banner['image_path']) ?>" 
                                             class="img-fluid shadow-sm" style="max-height: 80px; width: 100%; object-fit: contain;">
                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-0 group-hover-opacity-10 transition-all d-flex align-items-center justify-content-center">
                                            <a href="<?= base_url('uploads/banners/'.$banner['image_path']) ?>" target="_blank" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-eye"></i></a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small mb-1"><?= esc($banner['title']) ?></div>
                                    <div class="mb-1">
                                        <?php 
                                            $badge_class = 'bg-secondary';
                                            $pos_text = esc($banner['position']);
                                            if($pos_text == 'top_banner') { $badge_class = 'bg-info'; $pos_text = 'Top (1320x180)'; }
                                            if($pos_text == 'slide_ad') { $badge_class = 'bg-warning text-dark'; $pos_text = 'Slider (1200x675)'; }
                                            if($pos_text == 'sidebar_ad') { $badge_class = 'bg-primary'; $pos_text = 'Sidebar (350x600)'; }
                                            if($pos_text == 'category_ad') { $badge_class = 'bg-dark'; $pos_text = 'Category (1100x150)'; }
                                        ?>
                                        <span class="badge <?= $badge_class ?> rounded-pill" style="font-size: 10px;"><?= $pos_text ?></span>
                                    </div>
                                    <?php if(!empty($banner['summary'])): ?>
                                        <div class="text-muted extra-small text-truncate" style="max-width: 250px;"><?= esc($banner['summary']) ?></div>
                                    <?php endif; ?>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="bi bi-link-45deg text-primary me-1"></i>
                                        <a href="<?= esc($banner['link_url']) ?>" target="_blank" class="text-primary text-decoration-none truncate-link" style="font-size: 10px;"><?= esc($banner['link_url']) ?></a>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 border border-success-subtle" style="font-size: 10px;">Active</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                        <a href="<?= base_url('admin/banners/delete/'.$banner['id']) ?>" 
                                           class="btn btn-white btn-sm px-3 text-danger border-0" 
                                           onclick="return confirm('ఈ బ్యానర్‌ను ఖచ్చితంగా తొలగించాలా?')"
                                           title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60" class="opacity-25 mb-3">
                                    <p class="text-muted small fw-medium">ప్రస్తుతం ఎటువంటి బ్యానర్లు లేవు.</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-subtle { background-color: #f8fafc !important; }
    .transition-all { transition: all 0.3s ease; }
    .group:hover .group-hover-opacity-10 { opacity: 0.4 !important; }
    .truncate-link { display: inline-block; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.05); }
    .upload-area { transition: background 0.3s ease; }
    .upload-area:hover { background-color: #f1f5f9 !important; border-color: #0d6efd !important; }
    .extra-small { font-size: 10.5px; }
</style>

<script>
    // Size Guide Update Script
    function updateSizeGuide() {
        const select = document.getElementById('bannerPosition');
        const guide = document.getElementById('sizeGuide');
        const selectedOption = select.options[select.selectedIndex];
        guide.innerHTML = 'Recommended: ' + selectedOption.getAttribute('data-size') + ' px';
    }

    // Image Preview Script
    document.getElementById('bannerInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('bannerPreview');
        const icon = this.parentElement.querySelector('.bi-image');
        const text = this.parentElement.querySelector('p');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                icon.classList.add('d-none');
                text.classList.add('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<?= view('admin/includes/footer') ?>