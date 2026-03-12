<?= view('reporter/includes/header', ['title' => 'కొత్త వార్తను జోడించండి | NToday Reporter']) ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<style>
    .card { border: none; border-radius: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .form-label { font-weight: 700; color: var(--ntoday-dark); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    
    /* Summernote Styling */
    .note-editor.note-frame { border: 2px solid #e2e8f0; border-radius: 1rem; overflow: hidden; }
    .note-toolbar { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
    
    /* Dynamic Sections Styling */
    .location-box, .subcat-box { 
        padding: 20px; background: #fdf2f2; border-radius: 1rem; 
        border: 1px solid #fee2e2; border-left: 5px solid #dc3545; 
    }
    
    /* Image Preview Styling */
    .image-preview-wrapper { 
        width: 100%; min-height: 200px; background: #f1f5f9; 
        border: 2px dashed #cbd5e1; border-radius: 1rem; 
        display: flex; align-items: center; justify-content: center; cursor: pointer;
        position: relative; overflow: hidden;
    }
    #previewImageDisplay { width: 100%; border-radius: 1rem; object-fit: cover; }
    
    .preview-box { border: 1px solid #edf2f7; min-height: 150px; line-height: 1.6; border-radius: 1rem; background: #fff; }
    .btn-publish { 
        background: linear-gradient(135deg, #ef4444, #b91c1c); 
        border: none; padding: 1rem; border-radius: 50px; font-weight: bold; 
        transition: all 0.3s;
    }
    .btn-publish:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3); }
    .cursor-pointer { cursor: pointer; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-11">
            <div class="d-flex align-items-center justify-content-between mb-4 px-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1">వార్తను ప్రచురించండి</h2>
                    <p class="text-muted mb-0">సరికొత్త వార్తలను వేగంగా ప్రజలకు చేరవేయండి.</p>
                </div>
                <a href="<?= base_url('reporter/dashboard') ?>" class="btn btn-outline-dark rounded-pill px-4">Dashboard</a>
            </div>

            <form action="<?= base_url('reporter/store') ?>" method="POST" enctype="multipart/form-data" id="newsForm">
                <?= csrf_field() ?>
                
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card p-4 mb-4">
                            <div class="mb-4">
                                <label class="form-label">వార్త శీర్షిక (Title)</label>
                                <input type="text" name="title" id="newsTitle" class="form-control form-control-lg border-2 shadow-none fw-bold" placeholder="శీర్షిక రాయండి..." maxlength="150" required>
                                <div id="charCount" class="text-muted small mt-1">అక్షరాలు: 0 / 150</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">వార్తా సమాచారం (Content)</label>
                                <textarea name="content" id="summernote"></textarea>
                            </div>

                            <div class="bg-light p-4 rounded-4 border">
                                <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-eye-fill me-2"></i>Live Preview</h6>
                                <h4 id="previewTitle" class="fw-bold mb-3"></h4>
                                <div id="previewContent" class="preview-box p-3"></div>
                            </div>

                            <div class="row g-3 mt-4 p-3 bg-white rounded-4 shadow-sm">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control border-0 bg-light" placeholder="Tags...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Meta Description</label>
                                    <textarea name="meta_description" id="meta_desc" rows="1" class="form-control border-0 bg-light" placeholder="Summary..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card p-4 mb-4">
                            <label class="form-label d-block mb-3">వార్త చిత్రం (Featured Image)</label>
                            <div class="image-preview-wrapper mb-3" id="uploadTrigger" onclick="document.getElementById('newsImg').click()">
                                <div id="placeholder" class="text-center">
                                    <i class="bi bi-image-fill fs-1 text-muted opacity-50"></i>
                                    <p class="small text-muted mb-0">క్లిక్ చేసి ఫోటోను ఎంచుకోండి</p>
                                </div>
                                <img id="previewImageDisplay" class="d-none">
                                <button type="button" id="resetImgBtn" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle d-none" onclick="resetImage(event)"><i class="bi bi-x"></i></button>
                            </div>
                            <input type="file" id="newsImg" name="featured_image" class="d-none" accept="image/*">
                            <input type="hidden" name="cropped_image" id="croppedImageInput">
                        </div>

                        <div class="card p-4 mb-4 shadow-sm border-0 rounded-4">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <i class="bi bi-tags-fill me-2 text-danger"></i> వార్త విభాగం (Category)
                                </label>
                                <select name="category_id" id="mainCategory" class="form-select border-2 fw-bold shadow-none" required>
                                    <option value="">-- విభాగం ఎంచుకోండి --</option>
                                    <?php if(!empty($categories)): ?>
                                        <optgroup label="ప్రధాన విభాగాలు">
                                            <?php foreach($categories as $cat): ?>
                                                <?php if($cat['type'] == 'main' && $cat['IsActice'] == 1): ?>
                                                    <option value="<?= $cat['id'] ?>" data-type="main"><?= esc($cat['name']) ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="రాష్ట్ర వార్తలు">
                                            <?php foreach($categories as $cat): ?>
                                                <?php if($cat['type'] == 'state' && $cat['IsActice'] == 1): ?>
                                                    <option value="<?= $cat['id'] ?>" data-type="state"><?= esc($cat['name']) ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div id="subCategorySection" class="subcat-box mb-4 d-none animate__animated animate__fadeIn">
                                <label class="form-label small text-primary mb-2">ఉప విభాగం (Sub Category)</label>
                                <select name="sub_category_id" id="sub_category" class="form-select shadow-none"></select>
                            </div>

                            <div id="locationSection" class="location-box mb-4 d-none animate__animated animate__fadeIn">
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-2">జిల్లా (District)</label>
                                    <select name="district_id" id="district" class="form-select mb-3 shadow-none"></select>
                                    
                                    <label class="form-label small text-muted mb-2">మండలం (Mandal)</label>
                                    <select name="mandal_id" id="mandal" class="form-select mb-3 shadow-none"></select>
                                    
                                    <label class="form-label small text-muted mb-2">గ్రామం (Village)</label>
                                    <select name="village_id" id="village" class="form-select shadow-none"></select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" name="is_breaking_news" id="breaking">
                                    <label class="form-check-label fw-bold text-danger ms-2" for="breaking">బ్రేకింగ్ న్యూస్?</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-publish w-100 text-white fs-5 shadow-lg py-3 rounded-pill">
                                <i class="bi bi-send-check-fill me-2"></i> వార్తను పంపండి
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cropModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">ఫోటోను క్రాప్ చేయండి (16:9)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 bg-secondary">
                <img id="imageToCrop" style="max-width: 100%; display: block;">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-pill px-5 fw-bold" id="cropButton">Crop & Use</button>
            </div>
        </div>
    </div>
</div>

<?= view('reporter/includes/footer') ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    // 1. Summernote Setup
    $('#summernote').summernote({
        placeholder: 'వార్త వివరాలను ఇక్కడ రాయండి...',
        height: 350,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview']]
        ],
        callbacks: {
            onChange: function(contents) {
                $('#previewContent').html(contents);
            }
        }
    });

    // 2. Title Counter & Preview
    $('#newsTitle').on('input', function() {
        const len = $(this).val().length;
        $('#charCount').text(`అక్షరాలు: ${len} / 150`);
        $('#previewTitle').text($(this).val());
        $('#meta_desc').val($(this).val().substring(0, 155));
    });

    // 3. Image Cropping (Admin-style)
    let cropper;
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
    const imageToCrop = document.getElementById('imageToCrop');

    $('#newsImg').change(function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imageToCrop.src = event.target.result;
                cropModal.show();
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $('#cropModal').on('shown.bs.modal', function() {
        cropper = new Cropper(imageToCrop, { aspectRatio: 16 / 9, viewMode: 2 });
    }).on('hidden.bs.modal', function() {
        if (cropper) { cropper.destroy(); cropper = null; }
    });

    $('#cropButton').click(function() {
        const canvas = cropper.getCroppedCanvas({ width: 1280, height: 720 });
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        $('#previewImageDisplay').attr('src', dataUrl).removeClass('d-none');
        $('#placeholder').addClass('d-none');
        $('#resetImgBtn').removeClass('d-none');
        $('#croppedImageInput').val(dataUrl);
        cropModal.hide();
    });

    // 4. Dynamic Category Hierarchy (Corrected & Enhanced)
    $('#mainCategory').change(function() {
        const parentId = $(this).val();
        const type = $(this).find(':selected').data('type');

        $('#locationSection, #subCategorySection').addClass('d-none');
        resetDropdown('#district', 'జిల్లాను');
        resetDropdown('#sub_category', 'సబ్ కేటగిరీని');

        if (!parentId) return;

        if (type === 'state') {
            $('#locationSection').removeClass('d-none');
            fetchData(parentId, '#district', 'జిల్లాను');
        } else if (type === 'main') {
            $('#subCategorySection').removeClass('d-none');
            fetchData(parentId, '#sub_category', 'సబ్ కేటగిరీని');
        }
    });

    $('#district').change(function() { 
        resetDropdown('#mandal', 'మండలాన్ని');
        fetchData($(this).val(), '#mandal', 'మండలాన్ని'); 
    });
    
    $('#mandal').change(function() { 
        resetDropdown('#village', 'గ్రామాన్ని');
        fetchData($(this).val(), '#village', 'గ్రామాన్ని'); 
    });

    function fetchData(parentId, target, label) {
        if (!parentId) return;
        const $target = $(target);
        $target.prop('disabled', true).html('<option>లోడ్ అవుతోంది...</option>');
        
        // Use reporter specific route
        const baseUrl = "<?= rtrim(base_url(), '/') ?>";
        const apiUrl = `${baseUrl}/reporter/categories/getChildren/${parentId}`;

        console.log("Fetching from:", apiUrl);

        $.get(apiUrl, function(data) {
            let html = `<option value="">-- ${label} ఎంచుకోండి --</option>`;
            if (data && data.length > 0) {
                data.forEach(item => {
                    if(item.IsActice == 1 || item.IsActive == 1) {
                        html += `<option value="${item.id}">${item.name}</option>`;
                    }
                });
            }
            $target.prop('disabled', false).html(html);
        }).fail(function() {
            $target.prop('disabled', false).html('<option value="">డేటా దొరకలేదు</option>');
        });
    }

    function resetDropdown(target, label) {
        $(target).html(`<option value="">-- ${label} ఎంచుకోండి --</option>`);
    }

    window.resetImage = function(e) {
        if(e) e.stopPropagation();
        $('#newsImg').val('');
        $('#previewImageDisplay').addClass('d-none').attr('src', '');
        $('#placeholder').removeClass('d-none');
        $('#resetImgBtn').addClass('d-none');
        $('#croppedImageInput').val('');
    };
});
</script>