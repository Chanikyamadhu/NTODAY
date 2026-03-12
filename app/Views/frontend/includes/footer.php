<footer class="ntoday-footer pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <img src="<?= base_url('assets/images/Logo-R.png') ?>" alt="NToday Logo" class="mb-3" style="height: 55px; filter: brightness(0) invert(1);">
                <p class="footer-para small">
                    NToday: నిఖార్సైన వార్తలకు నిలయం. తెలుగు రాష్ట్రాల తాజా అప్‌డేట్స్, రాజకీయం, సినిమా, క్రీడలు మరియు ఇతర ముఖ్యమైన సమాచారాన్ని వేగంగా, ఖచ్చితత్వంతో మీకు అందిస్తున్నాము.
                </p>
                
                <div class="visitor-counter-section mt-4 p-3 rounded-4 d-inline-block" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="d-flex align-items-center gap-3 px-2">
                        <div class="icon-circle bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-users text-success"></i>
                        </div>
                        <div>
                            <small class="text-white opacity-50 d-block tracking-wider" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 700;">మా సందర్శకులు (Total Visitors)</small>
                            <span class="h4 fw-black mb-0 text-white"><?= number_format($total_views ?? 125430) ?></span>
                        </div>
                    </div>
                </div>

                <div class="social-icons mt-4">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading mb-4">ముఖ్యమైన లింకులు</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= base_url('/') ?>" class="footer-link-v2">హోమ్ (Home)</a></li>
                    <li class="mb-2"><a href="<?= base_url('trending') ?>" class="footer-link-v2">ట్రెండింగ్ వార్తలు</a></li>
                    <li class="mb-2"><a href="<?= base_url('videos') ?>" class="footer-link-v2">వీడియో గ్యాలరీ</a></li>
                    <li class="mb-2"><a href="<?= base_url('signup') ?>" class="footer-link-v2">రిపోర్టర్ అవ్వండి</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading mb-4">పాలసీలు & నిబంధనలు</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= base_url('privacy_policy') ?>" class="footer-link-v2">Privacy Policy</a></li>
                    <li class="mb-2"><a href="<?= base_url('terms_conditions') ?>" class="footer-link-v2">Terms & Conditions</a></li>
                    <li class="mb-2"><a href="<?= base_url('editors_policy') ?>" class="footer-link-v2">Editors Policy</a></li>
                    <li class="mb-2"><a href="<?= base_url('reporters_policy') ?>" class="footer-link-v2">Reporters Policy</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading mb-4">సంప్రదించండి</h6>
                <ul class="list-unstyled small">
                    <li class="mb-3 d-flex align-items-start footer-text-v2">
                        <i class="fas fa-map-marker-alt me-3 mt-1 text-warning"></i>
                        <span>హైదరాబాద్, తెలంగాణ, భారతదేశం.</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="fas fa-envelope me-3 text-warning"></i>
                        <a href="mailto:info@ntoday.com" class="footer-link-v2">info@ntoday.com</a>
                    </li>
                    <li class="mb-3 d-flex align-items-center">
                        <i class="fas fa-phone-alt me-3 text-warning"></i>
                        <span class="footer-text-v2">+91 98765 43210</span>
                    </li>
                    <li class="mt-4">
                        <a href="<?= base_url('contact-us') ?>" class="btn btn-yellow px-4 rounded-pill">Contact Us Form</a>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="my-5 border-light opacity-10">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start footer-text-v2 small">
                © 2026 <strong>NToday Media</strong>. All Rights Reserved.
            </div>
            <div class="col-md-6 text-center text-md-end footer-text-v2 small">
                Handcrafted by <a href="https://www.concitomind.com" class="text-warning text-decoration-none fw-bold">Concito Mind Solutions</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .ntoday-footer {
        background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 40%, #780000 100%) !important;
        border-top: 5px solid #ff0000;
    }

    .footer-heading {
        color: #00ff00 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 1.1rem;
        letter-spacing: 1px;
        position: relative;
        padding-bottom: 12px;
        margin-bottom: 20px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    .footer-heading::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 40px;
        height: 3px;
        background: #00ff00 !important;
    }

    .footer-para, .footer-text-v2, .footer-text-v2 span {
        color: #ffffff !important;
        opacity: 1 !important;
    }

    .footer-link-v2 {
        color: #ffffff !important;
        text-decoration: none !important;
        transition: 0.3s all ease;
        display: inline-block;
    }

    .footer-link-v2:hover {
        color: #ffc107 !important;
        padding-left: 8px;
    }

    .social-link {
        width: 42px;
        height: 42px;
        background: rgba(255, 255, 255, 0.1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #ffffff !important;
        margin-right: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .social-link:hover {
        background: #ff0000;
        color: #ffffff !important;
        transform: translateY(-5px) scale(1.1);
        border-color: #ff0000;
        text-decoration: none !important;
        box-shadow: 0 8px 15px rgba(255, 0, 0, 0.3);
    }

    .social-link i {
        font-size: 18px;
    }

    .btn-yellow {
        background: #ffc107 !important;
        color: #000000 !important;
        font-weight: 700;
        border: none;
    }
    
    .fw-black {
        font-weight: 900;
    }

    .tracking-wider {
        letter-spacing: 0.05rem;
    }
</style>