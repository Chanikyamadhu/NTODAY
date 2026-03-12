<?= view('admin/includes/header', ['title' => 'వార్తను సవరించండి']) ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold text-slate-800 mb-1">వార్తను సవరించండి</h3>
                    <p class="text-muted small mb-0">మార్పులు చేసి 'సవరణలను సేవ్ చేయి' బటన్ నొక్కండి.</p>
                </div>
                <a href="<?= base_url('admin/news/manage') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i> వెనక్కి
                </a>
            </div>

            <form action="<?= base_url('admin/news/update/'.$news['id']) ?>" method="POST" enctype="multipart/form-data" id="editNewsForm">
                <?= csrf_field() ?>
                
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small uppercase mb-2">News Title</label>
                                <input type="text" name="title" id="newsTitle" value="<?= esc($news['title']) ?>" 
                                    class="form-control form-control-lg border-slate-200 rounded-3 shadow-none fw-bold" maxlength="200" required>
                                <div id="charCount" class="text-muted small mt-1">అక్షరాల సంఖ్య: <?= strlen(esc($news['title'])) ?> / 200</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small uppercase mb-2">Content</label>
                                <div id="editor-container">
                                    <textarea name="content" id="summernote" class="form-control"><?= $news['content'] ?></textarea>
                                </div>
                            </div>

                            <div class="border-top pt-4 mt-4">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-eye me-2"></i>Live Preview</h6>
                                <div class="preview-box p-4 border rounded-4 bg-white shadow-inner">
                                    <h4 id="previewTitle" class="fw-bold text-dark mb-3" style="font-family: 'Mandali', sans-serif;"><?= esc($news['title']) ?></h4>
                                    <div id="previewContent" class="news-body-content">
                                        <?= $news['content'] ?>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top pt-4 mt-4">
                                <h6 class="fw-bold mb-3 text-slate-700"><i class="bi bi-search me-2"></i>SEO & Metadata</h6>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" value="<?= esc($news['meta_keywords'] ?? '') ?>" class="form-control border-slate-200 shadow-none rounded-3">
                                </div>
                                <div>
                                    <label class="form-label text-muted small fw-bold">Meta Description</label>
                                    <textarea name="meta_description" rows="2" class="form-control border-slate-200 shadow-none rounded-3"><?= esc($news['meta_description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="sticky-top" style="top: 20px;">
                            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                                <label class="form-label fw-bold text-dark small uppercase d-block mb-3">Featured Image</label>
                                <div class="preview-container mb-3 position-relative">
                                    <?php 
                                        $img_path = 'uploads/news/'.$news['featured_image'];
                                        $display_img = (!empty($news['featured_image']) && file_exists(FCPATH . $img_path)) ? base_url($img_path) : base_url('assets/images/no-image.png');
                                    ?>
                                    <img id="previewImage" src="<?= $display_img ?>" class="img-fluid rounded-4 border shadow-sm w-100">
                                </div>
                                <div class="mt-2">
                                    <input type="file" id="newsImg" name="featured_image" class="form-control form-control-sm border-slate-200 shadow-none" accept="image/*">
                                    <small class="text-muted mt-1 d-block italic">మార్చాలనుకుంటేనే కొత్త ఫోటో ఎంచుకోండి.</small>
                                </div>
                                <input type="hidden" name="cropped_image" id="croppedImageInput">
                            </div>

                            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark small uppercase mb-2">వార్త విభాగం</label>
                                    <select name="category_id" id="mainCategory" class="form-select border-slate-200 shadow-none fw-bold" required>
                                        <option value="">కేటగిరీని ఎంచుకోండి</option>
                                        <?php foreach($categories as $cat): ?>
                                            <?php if($cat['IsActice'] == 1 && ($cat['type'] == 'main' || $cat['type'] == 'state')): ?>
                                                <option value="<?= $cat['id'] ?>" data-type="<?= $cat['type'] ?>" <?= ($news['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                                    <?= $cat['name'] ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div id="subCategorySection" class="mb-4 <?= ($news['sub_category_id']) ? '' : 'd-none' ?>">
                                    <label class="form-label small fw-bold text-primary mb-2">సబ్ కేటగిరీ</label>
                                    <select name="sub_category_id" id="sub_category" class="form-select border-slate-200 shadow-none"></select>
                                </div>

                                <div id="locationSection" class="<?= ($news['district_id']) ? '' : 'd-none' ?>">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">జిల్లా</label>
                                        <select name="district_id" id="district" class="form-select border-slate-200 shadow-none mb-2"></select>
                                        <label class="form-label small fw-bold text-muted">మండలం</label>
                                        <select name="mandal_id" id="mandal" class="form-select border-slate-200 shadow-none mb-2"></select>
                                        <label class="form-label small fw-bold text-muted">గ్రామం</label>
                                        <select name="village_id" id="village" class="form-select border-slate-200 shadow-none"></select>
                                    </div>
                                </div>

                                <div class="mb-3 border-top pt-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_breaking_news" value="1" id="breakingNewsCheck" <?= ($news['is_breaking_news'] == 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold text-danger" for="breakingNewsCheck">
                                            Is Breaking News? (ముఖ్యాంశం)
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-muted small uppercase">Status</label>
                                    <select name="status" class="form-select border-slate-200 shadow-none">
                                        <option value="0" <?= ($news['status'] == 0) ? 'selected' : '' ?>>Draft (పెండింగ్)</option>
                                        <option value="1" <?= ($news['status'] == 1) ? 'selected' : '' ?>>Published (ప్రచురించు)</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg">
                                    <i class="bi bi-send-check-fill me-2"></i> సవరణలను సేవ్ చేయి
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cropModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imageToCrop" style="max-width: 100%;">
            </div>
            <div class="modal-footer">
                <button type="button" id="cropButton" class="btn btn-primary">Crop & Save</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    // 1. Title Count & Preview
    const titleInput = document.getElementById('newsTitle');
    const countDisplay = document.getElementById('charCount');
    const previewTitle = document.getElementById('previewTitle');

    titleInput.addEventListener('input', function() {
        const length = this.value.length;
        countDisplay.innerText = `అక్షరాల సంఖ్య: ${length} / 200`;
        previewTitle.innerText = this.value;
    });

    // 2. Summernote with Color Options & Preview
    $('#summernote').summernote({
        placeholder: 'పూర్తి వివరాలను ఇక్కడ రాయండి...',
        tabsize: 2,
        height: 350,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'italic', 'clear', 'strikethrough']],
            ['color', ['color']], 
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview']],
            ['history', ['undo', 'redo']]
        ],
        callbacks: {
            onChange: function(contents, $editable) {
                $('#previewContent').html(contents);
            },
            onImageUpload: function(files) {
                uploadEditorImage(files[0]);
            }
        }
    });

    // 3. లొకేషన్ మరియు పాత ఐడిల ఫంక్షనాలిటీ
    const oldSubCat   = "<?= $news['sub_category_id'] ?? '' ?>";
    const oldDistrict = "<?= $news['district_id'] ?? '' ?>";
    const oldMandal   = "<?= $news['mandal_id'] ?? '' ?>";
    const oldVillage  = "<?= $news['village_id'] ?? '' ?>";

    function handleCategoryChange(isInitialLoad = false) {
        const selectedOption = $('#mainCategory').find(':selected');
        const type = selectedOption.data('type');
        const parentId = $('#mainCategory').val();

        if (!isInitialLoad) {
            $('#locationSection, #subCategorySection').addClass('d-none');
        }
        
        if (!parentId || parentId == 0) return;

        if (type === 'state') {
            $('#locationSection').removeClass('d-none');
            fetchData(parentId, '#district', 'జిల్లాను', isInitialLoad ? oldDistrict : null);
        } else if (type === 'main') {
            $('#subCategorySection').removeClass('d-none');
            fetchData(parentId, '#sub_category', 'సబ్ కేటగిరీని', isInitialLoad ? oldSubCat : null);
        }
    }

    handleCategoryChange(true);

    $('#mainCategory').change(function() { handleCategoryChange(false); });
    $('#district').change(function() { fetchData($(this).val(), '#mandal', 'మండలాన్ని', ($(this).val() == oldDistrict) ? oldMandal : null); });
    $('#mandal').change(function() { fetchData($(this).val(), '#village', 'గ్రామాన్ని', ($(this).val() == oldMandal) ? oldVillage : null); });

    function fetchData(parentId, target, label, preSelectId = null) {
        if (!parentId || parentId == 0) return;
        $(target).prop('disabled', true).html('<option>లోడ్ అవుతోంది...</option>');
        $.get(`<?= base_url('admin/categories/getChildren/') ?>/${parentId}`, function(data) {
            let html = `<option value="">${label} ఎంచుకోండి</option>`;
            data.forEach(item => { 
                if(item.IsActice == 1) {
                    let selected = (preSelectId != null && preSelectId == item.id) ? 'selected' : '';
                    html += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                }
            });
            $(target).prop('disabled', false).html(html);
            if(preSelectId != null) $(target).trigger('change');
        });
    }

    // 4. ఇమేజ్ అప్‌లోడ్ మరియు క్రాపర్
    function uploadEditorImage(file) {
        let data = new FormData();
        data.append("upload", file);
        data.append("<?= csrf_token() ?>", "<?= csrf_hash() ?>");
        $.ajax({
            url: "<?= base_url('admin/news/uploadImage') ?>",
            processData: false, contentType: false, data: data, type: "POST",
            success: function(url_obj) {
                if(url_obj.uploaded) $('#summernote').summernote('insertImage', url_obj.url);
            }
        });
    }

    let cropper;
    const newsImg = document.getElementById('newsImg');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    newsImg.addEventListener('change', function(e) {
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
        const croppedImg = canvas.toDataURL('image/jpeg', 0.9);
        $('#previewImage').attr('src', croppedImg);
        $('#croppedImageInput').val(croppedImg);
        cropModal.hide();
    });
});
</script>

<style>
    .cursor-pointer { cursor: pointer; }
    .note-editor.note-frame { border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .note-toolbar { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
    .preview-box { border: 1px solid #edf2f7; min-height: 200px; line-height: 1.6; }
    .news-body-content img { max-width: 100%; height: auto; border-radius: 8px; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05); }
    .form-check-input:checked { background-color: #dc3545; border-color: #dc3545; }
</style>