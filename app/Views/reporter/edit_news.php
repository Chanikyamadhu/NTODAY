<!DOCTYPE html>
<html lang="te">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NToday - వార్తను సవరించండి</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .edit-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); background: #fff; }
        .btn-update { background-color: #28a745; color: white; border-radius: 8px; font-weight: 600; }
        .btn-update:hover { background-color: #218838; color: white; }
        .current-img-preview { width: 150px; border-radius: 10px; border: 2px solid #ddd; }
        .location-box { background: #f8f9fa; padding: 15px; border-radius: 10px; border-left: 5px solid #0d6efd; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url('reporter/dashboard') ?>">
            <i class="fas fa-edit me-2 text-warning"></i> NToday Edit Portal
        </a>
        <a href="<?= base_url('reporter/dashboard') ?>" class="btn btn-outline-light btn-sm rounded-pill">తిరిగి వెళ్లండి (Back)</a>
    </div>
</nav>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card edit-card p-4 p-md-5">
                <h3 class="fw-bold text-dark border-bottom pb-3 mb-4">వార్తను సవరించండి (Edit News)</h3>
                
                <form action="<?= base_url('reporter/update/'.$news['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label fw-bold">వార్త శీర్షిక</label>
                        <input type="text" name="title" class="form-control form-control-lg" 
                               value="<?= esc($news['title']) ?>" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">వార్త విభాగం/రాష్ట్రం</label>
                            <select id="main_vibagam" name="category_id" class="form-select" required>
                                <option value="">-- ఎంచుకోండి --</option>
                                
                                <optgroup label="రాష్ట్రాలు">
                                    <?php foreach($states as $state): ?>
                                    <option value="<?= $state['id'] ?>" data-type="state" 
                                        <?= (isset($news['state_id']) && $news['state_id'] == $state['id']) ? 'selected' : '' ?>>
                                        <?= $state['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                                </optgroup>

                                <optgroup label="విభాగాలు">
                                    <?php foreach($main_categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" data-type="main" 
                                            <?= (isset($news['category_id']) && $news['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= $cat['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">చిత్రాన్ని మార్చండి</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            <div class="mt-2">
                                <img src="<?= base_url('uploads/news/'.$news['featured_image']) ?>" class="current-img-preview">
                            </div>
                        </div>
                    </div>

                    <div id="location_section" class="location-box row mb-4" style="display: <?= ($news['state_id']) ? 'flex' : 'none' ?>;">
                        <div class="col-md-4">
                            <label class="small fw-bold">జిల్లా</label>
                            <select id="district" name="district_id" class="form-select"></select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">మండలం</label>
                            <select id="mandal" name="mandal_id" class="form-select"></select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">గ్రామం</label>
                            <input type="text" name="village" value="<?= esc($news['village_id']) ?>" class="form-control" placeholder="గ్రామం పేరు">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">వార్త వివరాలు</label>
                        <textarea name="content" id="editor2" required><?= esc($news['content']) ?></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= base_url('reporter/dashboard') ?>" class="btn btn-light px-4 me-md-2">రద్దు చేయి</a>
                        <button type="submit" class="btn btn-update px-5">వార్తను అప్‌డేట్ చేయి</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    CKEDITOR.replace('editor2', { height: 400 });

    $(document).ready(function() {
        // పేజీ లోడ్ అయినప్పుడు పాత లొకేషన్ డేటా తెప్పించడం
        let oldState = "<?= $news['state_id'] ?>";
        let oldDist = "<?= $news['district_id'] ?>";
        let oldMand = "<?= $news['mandal_id'] ?>";

        if(oldState) {
            loadLocation(oldState, '#district', 'జిల్లా', oldDist);
            if(oldDist) loadLocation(oldDist, '#mandal', 'మండలం', oldMand);
        }

        $('#main_vibagam').change(function() {
            let type = $(this).find(':selected').data('type');
            if(type === 'state') {
                $('#location_section').fadeIn();
                loadLocation($(this).val(), '#district', 'జిల్లా');
            } else {
                $('#location_section').hide();
            }
        });

        $('#district').change(function() {
            loadLocation($(this).val(), '#mandal', 'మండలం');
        });

        function loadLocation(parentId, target, label, selectedId = null) {
            $.get('<?= base_url("categories/getChildren/") ?>' + parentId, function(data) {
                let html = `<option value="">-- ${label} ఎంచుకోండి --</option>`;
                data.forEach(item => {
                    let selected = (item.id == selectedId) ? 'selected' : '';
                    html += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                });
                $(target).html(html);
            });
        }
    });
</script>
</body>
</html>