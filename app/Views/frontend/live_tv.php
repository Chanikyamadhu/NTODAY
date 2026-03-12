<?= view('frontend/includes/header', ['title' => $title]) ?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="fw-bold text-danger mb-4">
                <i class="bi bi-tv me-2"></i> లైవ్ టీవీ (Live TV)
            </h2>
            
            <div class="shadow-lg rounded-4 overflow-hidden border bg-black" style="aspect-ratio: 16/9;">
                <iframe 
                    width="100%" 
                    height="100%" 
                    src="https://www.youtube.com/embed/<?= $live_video_id ?>?autoplay=1" 
                    title="Live TV" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
                </iframe>
            </div>

            <div class="mt-4 p-4 bg-white rounded-4 shadow-sm">
                <h5 class="fw-bold">NToday Live Streaming</h5>
                <p class="text-muted small">తెలుగు రాష్ట్రాల తాజా వార్తలు మరియు బ్రేకింగ్ అప్‌డేట్స్ కోసం లైవ్ చూడండి.</p>
            </div>
        </div>
    </div>
</main>

<?= view('frontend/includes/footer') ?>