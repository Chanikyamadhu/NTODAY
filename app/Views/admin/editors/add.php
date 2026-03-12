<?= view('admin/includes/header', ['title' => $title]) ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h4 class="fw-bold mb-4">కొత్త ఎడిటర్‌ను చేర్చండి</h4>
                
                <form action="<?= base_url('admin/editors/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">పూర్తి పేరు (Display Name)</label>
                        <input type="text" name="display_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">ఈమెయిల్ (Email Address)</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">పాస్‌వర్డ్</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">Create Editor</button>
                        <a href="<?= base_url('admin/editors/manage') ?>" class="btn btn-light rounded-pill border">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= view('admin/includes/footer') ?>