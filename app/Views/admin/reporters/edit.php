<?= view('admin/includes/header', ['title' => 'రిపోర్టర్ ప్రొఫైల్ సవరణ']) ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold text-slate-800 mb-1">ప్రొఫెషనల్ ప్రొఫైల్ సవరణ</h3>
                    <p class="text-muted small mb-0">రిపోర్టర్ వ్యక్తిగత మరియు వృత్తిపరమైన వివరాలను ఇక్కడ అప్‌డేట్ చేయండి.</p>
                </div>
                <a href="<?= base_url('admin/reporters/manage') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i> వెనక్కి
                </a>
            </div>

            <form action="<?= base_url('admin/reporters/update/'.$reporter['id']) ?>" method="POST" enctype="multipart/form-data" id="profileForm">
                <?= csrf_field() ?>
                
                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">వ్యక్తిగత వివరాలు (Personal Info)</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3 text-center">
                                    <label class="form-label small fw-bold d-block text-start">Profile Picture</label>
                                    <div class="position-relative d-inline-block">
                                        <?php 
                                            $profile_path = 'uploads/profile/'.$reporter['profile_pic'];
                                            $display_img = (!empty($reporter['profile_pic']) && file_exists(FCPATH . $profile_path)) ? base_url($profile_path) : base_url('assets/images/default-user.png');
                                        ?>
                                        <img id="profilePreview" src="<?= $display_img ?>" class="rounded-circle border shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                                        <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle shadow" onclick="document.getElementById('profileInput').click()">
                                            <i class="bi bi-camera-fill"></i>
                                        </button>
                                    </div>
                                    <input type="file" id="profileInput" name="profile_pic" class="d-none" accept="image/*">
                                    <input type="hidden" name="cropped_profile_pic" id="croppedProfileInput">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Display Name</label>
                                    <input type="text" name="display_name" value="<?= esc($reporter['display_name']) ?>" class="form-control rounded-3" required>
                                    <label class="form-label small fw-bold mt-2">Full Name (As per ID)</label>
                                    <input type="text" name="full_name" value="<?= esc($reporter['full_name'] ?? '') ?>" class="form-control rounded-3">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Email Address</label>
                                    <input type="email" name="email" value="<?= esc($reporter['email']) ?>" class="form-control rounded-3" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Phone Number</label>
                                    <input type="text" name="phone" value="<?= esc($reporter['phone'] ?? '') ?>" class="form-control rounded-3">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Gender</label>
                                    <select name="gender" class="form-select rounded-3">
                                        <option value="Male" <?= ($reporter['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= ($reporter['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= ($reporter['gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Date of Birth</label>
                                    <input type="date" name="dob" value="<?= esc($reporter['dob'] ?? '') ?>" class="form-control rounded-3">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Address</label>
                                <textarea name="address" rows="2" class="form-control rounded-3"><?= esc($reporter['address'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label small fw-bold">Bio / About Reporter</label>
                                <textarea name="bio" rows="4" class="form-control rounded-3" placeholder="రిపోర్టర్ గురించి క్లుప్తంగా..."><?= esc($reporter['bio'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">వృత్తిపరమైన వివరాలు (Professional)</h6>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Area of Coverage (రిపోర్టింగ్ ఏరియా)</label>
                                <input type="text" name="area_coverage" value="<?= esc($reporter['area_coverage'] ?? '') ?>" class="form-control rounded-3" placeholder="Ex: Hyderabad, Warangal...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Pincode</label>
                                <input type="number" name="pincode" value="<?= esc($reporter['pincode'] ?? '') ?>" class="form-control rounded-3">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">News Tags (Expertise)</label>
                                <input type="text" name="news_tags" value="<?= esc($reporter['news_tags'] ?? '') ?>" class="form-control rounded-3" placeholder="Politics, Crime, Sports...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-danger">New Password (మార్చాలనుకుంటేనే)</label>
                                <input type="password" name="password" class="form-control rounded-3" placeholder="********">
                            </div>

                            <div class="mb-0">
                                <label class="form-label small fw-bold">Account Status</label>
                                <select name="status" class="form-select rounded-3 fw-bold <?= ($reporter['status'] == 1) ? 'text-success' : 'text-danger' ?>">
                                    <option value="1" <?= ($reporter['status'] == 1) ? 'selected' : '' ?>>Active (యాక్టివ్)</option>
                                    <option value="2" <?= ($reporter['status'] == 2) ? 'selected' : '' ?>>Inactive (బ్లాక్)</option>
                                    <option value="0" <?= ($reporter['status'] == 0) ? 'selected' : '' ?>>Pending (వెయిటింగ్)</option>
                                </select>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <button type="submit" class="btn btn-primary py-3 rounded-pill fw-bold shadow w-100">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> ప్రొఫైల్‌ను సేవ్ చేయి
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cropModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">ఫోటోను క్రాప్ చేయండి</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="img-container bg-light rounded-3 overflow-hidden">
                    <img id="imageToCrop" style="max-width: 100%; display: block;">
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold" id="cropButton">Crop & Use</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
$(document).ready(function() {
    let cropper;
    const profileInput = document.getElementById('profileInput');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    profileInput.addEventListener('change', function(e) {
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
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, // Square crop for profile
            viewMode: 2,
            dragMode: 'move'
        });
    }).on('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    $('#cropButton').click(function() {
        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
        const croppedImg = canvas.toDataURL('image/jpeg', 0.9);
        $('#profilePreview').attr('src', croppedImg);
        $('#croppedProfileInput').val(croppedImg);
        cropModal.hide();
    });
});
</script>

<?= view('admin/includes/footer') ?>