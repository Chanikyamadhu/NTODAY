<?= view('frontend/includes/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-danger text-white h-100">
                <h3 class="fw-bold mb-4">మమ్మల్ని సంప్రదించండి</h3>
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-geo-alt-fill me-2"></i> చిరునామా:</h6>
                    <p class="small opacity-75">NToday News Office, <br>హైదరాబాద్, తెలంగాణ.</p>
                </div>
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-envelope-fill me-2"></i> ఈమెయిల్:</h6>
                    <p class="small opacity-75">info@ntoday.in</p>
                </div>
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-telephone-fill me-2"></i> ఫోన్:</h6>
                    <p class="small opacity-75">+91 00000 00000</p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h4 class="fw-bold mb-3 text-dark">సందేశం పంపండి</h4>
                <form action="<?= base_url('contact/send') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">మీ పేరు</label>
                        <input type="text" class="form-control rounded-3" placeholder="ఉదా: కిరణ్" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">ఈమెయిల్</label>
                        <input type="email" class="form-control rounded-3" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">విషయం</label>
                        <input type="text" class="form-control rounded-3" placeholder="వార్త లేదా ప్రకటన గురించి" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">సందేశం</label>
                        <textarea class="form-control rounded-3" rows="4" placeholder="మీ సందేశాన్ని ఇక్కడ రాయండి..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">పంపు (Send)</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= view('frontend/includes/footer') ?>