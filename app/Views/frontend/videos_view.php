<?= view('frontend/includes/header', ['title' => $title]) ?>

<div class="container py-5">
    <h3 class="fw-bold border-start border-5 border-danger ps-2 mb-4">వీడియో వార్తలు</h3>
    
    <div class="row g-4">
        <?php if(!empty($youtube_videos)): foreach($youtube_videos as $video): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                <a href="<?= $video['link'] ?>" target="_blank" class="text-decoration-none">
                    <div class="position-relative">
                        <img src="<?= $video['thumbnail'] ?>" class="card-img-top" alt="<?= $video['title'] ?>">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="bi bi-play-circle-fill text-white fs-1 opacity-75"></i>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="card-title text-dark fw-bold line-clamp-2" style="font-size: 0.9rem;">
                            <?= esc($video['title']) ?>
                        </h6>
                    </div>
                </a>
            </div>
        </div>
        <?php endforeach; else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">ప్రస్తుతం ఎటువంటి వీడియోలు అందుబాటులో లేవు.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= view('frontend/includes/footer') ?>