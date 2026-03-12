<?= view('frontend/includes/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h2 class="fw-bold mb-4 border-bottom pb-3 text-dark">Editorial Policy</h2>
                
                <p class="text-muted small">చివరిగా సవరించిన తేదీ: <?= date('d M, Y') ?></p>

                <div class="policy-content" style="line-height: 1.8; color: #444;">
                    <section class="mb-4">
                        <h5 class="fw-bold text-danger"><i class="bi bi-check2-circle me-2"></i> 1. నిజాయితీ మరియు ఖచ్చితత్వం</h5>
                        <p>ప్రచురించబడే ప్రతి వార్త వాస్తవ ఆధారితంగా ఉండాలని NToday నమ్ముతుంది. మేము ఎటువంటి తప్పుడు సమాచారాన్ని ప్రోత్సహించము.</p>
                    </section>

                    <section class="mb-4">
                        <h5 class="fw-bold text-danger"><i class="bi bi-shield-check me-2"></i> 2. నిష్పాక్షికత</h5>
                        <p>వార్తల సేకరణలో మరియు ప్రచురణలో మేము ఎటువంటి రాజకీయ లేదా వ్యక్తిగత పక్షపాతం లేకుండా వ్యవహరిస్తాము.</p>
                    </section>

                    <section class="mb-4">
                        <h5 class="fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i> 3. సవరణలు (Corrections)</h5>
                        <p>ఒకవేళ మా వార్తలలో ఏదైనా పొరపాటు దొర్లితే, దానిని గుర్తించిన వెంటనే సరిదిద్ది పాఠకులకు తెలియజేస్తాము.</p>
                    </section>
                </div>

                <div class="mt-5">
                    <a href="<?= base_url('/') ?>" class="btn btn-dark rounded-pill px-4 fw-bold">
                        <i class="bi bi-house-door me-2"></i> తిరిగి హోమ్ పేజీకి
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('frontend/includes/footer') ?>