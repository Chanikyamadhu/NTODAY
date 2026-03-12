<?= view('frontend/includes/header', ['title' => $title]) ?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "headline": "<?= esc($category['name']) ?> News - NToday",
  "description": "<?= esc($meta_desc) ?>",
  "url": "<?= current_url() ?>"
}
</script>

<main class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-danger text-decoration-none">హోమ్</a></li>
            <li class="breadcrumb-item active"><?= esc($category['name']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold border-start border-5 border-danger ps-3 mb-4">
                <?= esc($category['name']) ?> విభాగం
            </h2>
        </div>
    </div>

    <div class="row g-4">
        <?php if(!empty($news_list)): ?>
            <?php foreach($news_list as $news): ?>
                <div class="col-md-4 col-sm-6">
                    <article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <a href="<?= base_url('news/'.$news['slug']) ?>" class="text-decoration-none text-dark">
                            <div class="position-relative">
                                <?php 
                                    // ఇమేజ్ ఉందో లేదో తనిఖీ చేసి, లేకపోతే default.png ని ఎంచుకుంటుంది
                                    $card_image = (!empty($news['featured_image'])) 
                                                ? base_url('uploads/news/'.$news['featured_image']) 
                                                : base_url('assets/images/default.png'); 
                                ?>
                                <img src="<?= $card_image ?>" 
                                    class="card-img-top" 
                                    alt="<?= esc($news['title']) ?>" 
                                    style="height: 200px; object-fit: cover;"
                                    loading="lazy">
                                <span class="position-absolute bottom-0 start-0 badge bg-danger m-2">
                                    <?= esc($category['name']) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-2" style="line-height: 1.4; height: 3em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    <?= esc($news['title']) ?>
                                </h5>
                                <div class="d-flex justify-content-between align-items-center mt-3 text-muted small">
                                    <span><i class="far fa-calendar-alt"></i> <?= date('d M, Y', strtotime($news['created_at'])) ?></span>
                                    <span><i class="far fa-eye"></i> <?= number_format($news['view_count'] ?? 0) ?></span>
                                </div>
                            </div>
                        </a>
                    </article>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <h4>ఈ కేటగిరీలో ప్రస్తుతం ఎటువంటి వార్తలు లేవు.</h4>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        <?= $pager->links() ?>
    </div>
</main>

<style>
    .card { transition: transform 0.3s ease; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .breadcrumb-item a:hover { color: #dc3545 !important; }
    /* Pagination Styling */
    .pagination .active .page-link { background-color: #dc3545; border-color: #dc3545; }
    .page-link { color: #1a1a1a; }
</style>

<?= view('frontend/includes/footer') ?>