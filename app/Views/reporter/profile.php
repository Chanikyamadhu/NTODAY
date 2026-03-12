<?= view('reporter/includes/header', ['title' => 'నా ప్రొఫైల్ | NToday Reporter']) ?>

<style>
    /* Styling Enhancements */
    body { background-color: #f4f7f6; font-family: 'Noto Sans Telugu', 'Inter', sans-serif; }
    
    .rounded-5 { border-radius: 2rem !important; }
    .card { border: none; transition: all 0.3s cubic-bezier(.25,.8,.25,1); }
    
    /* ప్రొఫైల్ బ్యానర్ గ్రేడియంట్ */
    .profile-banner {
        height: 140px; 
        background: linear-gradient(135deg, #0d1b2a 0%, #780000 100%);
    }

    .profile-img-container { margin-top: -75px; }

    .btn-edit-profile {
        background-color: #dc3545;
        border: none;
        transition: 0.3s;
    }
    .btn-edit-profile:hover {
        background-color: #b02a37;
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(220, 38, 38, 0.2);
    }

    .bg-light-soft { background-color: rgba(0, 0, 0, 0.03) !important; }

    /* Online pulse animation */
    .status-online {
        width: 22px; height: 22px;
        background-color: #28a745;
        border: 3px solid #fff;
        animation: pulse-green 2s infinite;
    }

    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    .info-label {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 1px;
        color: #6c757d;
        text-transform: uppercase;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card shadow-lg rounded-5 overflow-hidden mb-4 bg-white">
                <div class="profile-banner"></div>
                
                <div class="card-body text-center position-relative profile-img-container">
                    <div class="position-relative d-inline-block mb-3">
                        <?php 
                            $p_img = !empty($reporter['profile_pic']) ? base_url('uploads/profiles/'.$reporter['profile_pic']) : base_url('assets/images/default-user.png');
                        ?>
                        <img src="<?= $p_img ?>" 
                             class="rounded-circle border border-5 border-white shadow-lg" 
                             style="width: 140px; height: 140px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 rounded-circle status-online" title="Online Status"></span>
                    </div>

                    <h2 class="fw-bold mb-1 text-dark">
                        <?= esc($reporter['display_name']) ?>
                    </h2>
                    <p class="text-muted small mb-2">
                        <span class="badge bg-light text-dark border me-2">ID: #NT-<?= str_pad($reporter['id'], 4, '0', STR_PAD_LEFT) ?></span>
                        <span class="fw-bold text-danger text-uppercase small">
                            <i class="fas fa-certificate me-1"></i> Verified Reporter
                        </span>
                    </p>

                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <a href="<?= base_url('reporter/edit-profile') ?>" class="btn btn-edit-profile text-white px-4 rounded-pill fw-bold">
                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                        </a>
                        <a href="<?= base_url('reporter/dashboard') ?>" class="btn btn-outline-dark px-4 rounded-pill fw-bold">
                            <i class="fas fa-th-large me-2"></i> Dashboard
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-transparent border-top-0 pb-5 pt-2">
                    <div class="row g-0 justify-content-center text-center">
                        <div class="col-4 border-end">
                            <h4 class="fw-bold mb-0 text-danger"><?= number_format($total_news ?? 0) ?></h4>
                            <small class="info-label">Articles</small>
                        </div>
                        <div class="col-4 border-end">
                            <h4 class="fw-bold mb-0 text-primary"><?= number_format($total_views ?? 0) ?></h4>
                            <small class="info-label">Total Views</small>
                        </div>
                        <div class="col-4">
                            <h4 class="fw-bold mb-0 text-warning">Gold</h4>
                            <small class="info-label">Reporter Badge</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card border-0 shadow-sm rounded-4 bg-white">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="fw-bold mb-4 text-center text-dark">
                                <span class="pb-2 border-bottom border-danger border-3">వ్యక్తిగత వివరాలు</span>
                            </h5>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light-soft h-100 border">
                                        <label class="info-label d-block mb-1">ఈమెయిల్ అడ్రస్</label>
                                        <span class="fw-bold h6 mb-0 text-dark"><?= esc($reporter['email']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light-soft h-100 border">
                                        <label class="info-label d-block mb-1">మొబైల్ నంబర్</label>
                                        <span class="fw-bold h6 mb-0 text-dark"><?= esc($reporter['phone'] ?? 'Update Required') ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light-soft h-100 border">
                                        <label class="info-label d-block mb-1">రిపోర్టింగ్ ప్రాంతం</label>
                                        <span class="fw-bold h6 mb-0 text-dark"><?= esc($reporter['location_name'] ?? 'తెలంగాణ') ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light-soft h-100 border">
                                        <label class="info-label d-block mb-1">మెంబర్ సిన్స్</label>
                                        <span class="fw-bold h6 mb-0 text-dark"><?= date('F d, Y', strtotime($reporter['created_at'])) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-3 border-top text-center text-muted small">
                                <p class="mb-0"><i class="fas fa-shield-alt me-1 text-success"></i> మీ ఖాతా భద్రత కోసం పాస్‌వర్డ్ మరియు ఫోన్ నంబర్‌ను రహస్యంగా ఉంచండి.</p>
                                <a href="<?= base_url('reporter/logout') ?>" class="text-danger fw-bold text-decoration-none small mt-3 d-inline-block">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout Account
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= view('reporter/includes/footer') ?>