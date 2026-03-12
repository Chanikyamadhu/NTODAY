<?= view('frontend/includes/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h2 class="fw-bold mb-4 border-bottom pb-3">Privacy Policy</h2>
                
                <p class="text-muted">Last Updated: <?= date('d M, Y') ?></p>

                <section class="mb-4">
                    <h5 class="fw-bold text-danger">1. సమాచార సేకరణ (Information Collection)</h5>
                    <p>మా వెబ్‌సైట్ సందర్శించే పాఠకుల వ్యక్తిగత గోప్యతను మేము గౌరవిస్తాము. మీరు మా సేవలను ఉపయోగించినప్పుడు మాత్రమే అవసరమైన సమాచారాన్ని సేకరిస్తాము.</p>
                </section>

                <section class="mb-4">
                    <h5 class="fw-bold text-danger">2. కుకీలు (Cookies)</h5>
                    <p>యూజర్ ఎక్స్‌పీరియన్స్ మెరుగుపరచడానికి మేము కుకీలను ఉపయోగిస్తాము. మీరు బ్రౌజర్ సెట్టింగ్స్‌లో వీటిని నిలిపివేయవచ్చు.</p>
                </section>

                <section class="mb-4">
                    <h5 class="fw-bold text-danger">3. సంప్రదించండి</h5>
                    <p>మా ప్రైవసీ పాలసీ గురించి ఏవైనా సందేహాలు ఉంటే మాకు మెయిల్ చేయండి: <strong>info@ntoday.in</strong></p>
                </section>

                <div class="mt-4">
                    <a href="<?= base_url('/') ?>" class="btn btn-outline-danger rounded-pill px-4">తిరిగి హోమ్ పేజీకి</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('frontend/includes/footer') ?>