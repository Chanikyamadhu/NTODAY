<?= view('frontend/includes/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h2 class="fw-bold mb-4 border-bottom pb-3 text-dark">Terms & Conditions</h2>
                
                <p class="text-muted">చివరిగా అప్‌డేట్ చేసిన తేదీ: <?= date('d M, Y') ?></p>

                <div class="terms-content" style="line-height: 1.8;">
                    <section class="mb-4">
                        <h5 class="fw-bold text-danger">1. నిబంధనల అంగీకారం</h5>
                        <p>మా వెబ్‌సైట్‌ను సందర్శించడం ద్వారా మీరు ఇక్కడ పేర్కొన్న నిబంధనలకు కట్టుబడి ఉంటారని అంగీకరిస్తున్నారు.</p>
                    </section>

                    <section class="mb-4">
                        <h5 class="fw-bold text-danger">2. కంటెంట్ వినియోగం</h5>
                        <p>NToday లో ప్రచురించబడిన వార్తలు, ఫోటోలు మరియు వీడియోలు మా అనుమతి లేకుండా ఇతర ప్లాట్‌ఫారమ్‌లలో వాడటం చట్టవిరుద్ధం.</p>
                    </section>

                    <section class="mb-4">
                        <h5 class="fw-bold text-danger">3. బాధ్యత రాహిత్యం (Disclaimer)</h5>
                        <p>ప్రచురించబడిన వార్తల సమాచారం యొక్క ఖచ్చితత్వం కోసం మేము నిరంతరం కృషి చేస్తాము, అయితే ఏదైనా పొరపాట్లు జరిగితే దానికి మేము బాధ్యులం కాదు.</p>
                    </section>
                </div>

                <div class="mt-4">
                    <a href="<?= base_url('/') ?>" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">తిరిగి హోమ్ పేజీకి</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('frontend/includes/footer') ?>