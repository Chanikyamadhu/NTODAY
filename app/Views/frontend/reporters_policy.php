<?= view('frontend/includes/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h2 class="fw-bold mb-4 border-bottom pb-3 text-dark">Reporters Policy</h2>
                
                <p class="text-muted small">చివరిగా అప్‌డేట్ చేసిన తేదీ: <?= date('d M, Y') ?></p>

                <div class="policy-content" style="line-height: 1.8; color: #444;">
                    <section class="mb-4">
                        <h5 class="fw-bold text-danger"><i class="bi bi-person-badge me-2"></i> 1. రిపోర్టర్ల బాధ్యత</h5>
                        <p>ప్రతి రిపోర్టర్ తాను సేకరించే వార్త ఖచ్చితమైనదని మరియు ఎటువంటి పక్షపాతం లేనిదని నిర్ధారించుకోవాలి. ఆధారాలు లేని వార్తలను ప్రచురించకూడదు.</p>
                    </section>

                    <section class="mb-4">
                        <h5 class="fw-bold text-danger"><i class="bi bi-shield-lock me-2"></i> 2. నైతిక నిబంధనలు</h5>
                        <p>వ్యక్తిగత ప్రయోజనాల కోసం లేదా ఎవరినైనా కించపరిచే ఉద్దేశంతో వార్తలను రాయకూడదు. ఎల్లప్పుడూ జర్నలిజం విలువలను కాపాడాలి.</p>
                    </section>

                    <section class="mb-4">
                        <h5 class="fw-bold text-danger"><i class="bi bi-camera-video me-2"></i> 3. మీడియా కంటెంట్</h5>
                        <p>వార్తలకు సంబంధించిన ఫోటోలు మరియు వీడియోలు కాపీరైట్ ఉల్లంఘించకుండా ఉండాలి. సాధ్యమైనంత వరకు స్వయంగా సేకరించిన చిత్రాలనే వాడాలి.</p>
                    </section>
                </div>

                <div class="mt-5">
                    <a href="<?= base_url('/') ?>" class="btn btn-outline-danger rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-house-door me-2"></i> తిరిగి హోమ్ పేజీకి
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('frontend/includes/footer') ?>