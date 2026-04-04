<?php 
$bodyClass = 'yummy-page'; 
require __DIR__ . '/../../partials/header.php';
?>

<div style="background-color: #3a0d00; min-height: 100vh; color: #f4d9c6;">
    
    <!-- heading & description section -->
    <?php foreach ($vm->getSections() as $section => $elements): ?>
        <div class="section<?= htmlspecialchars($section) ?>">
            
            <?php foreach ($elements as $element): ?>
                <?= $element->render(); ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <div class="container py-5">
        <section class="restaurant-list">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="fw-bold text-white">Discover Restaurants</h2>
                <span class="text-white-50"><?= count($restaurants) ?> results found</span>
            </div>

            <div class="row g-4">
                <?php if (empty($restaurants)): ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-white-50">No restaurants are currently listed for this event.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 restaurant-card border-0 shadow-lg" style="background-color: #4a1608; color: white; border-radius: 15px; overflow: hidden;">
                                <div class="ratio ratio-16x9">
                                    <img src="<?= htmlspecialchars($restaurant->getImageUrl()) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($restaurant->getName()) ?>"
                                         style="object-fit: cover;">
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <span class="badge" style="background-color: rgba(244, 217, 198, 0.2); color: #f4d9c6; border: 1px solid #f4d9c6;">
                                            <?= htmlspecialchars($restaurant->getCuisine()) ?>
                                        </span>
                                    </div>

                                    <h5 class="card-title fw-bold mb-1" style="color: #f4d9c6;">
                                        <?= htmlspecialchars($restaurant->getName()) ?>
                                    </h5>
                                    
                                    <p class="small mb-3" style="color: #f5c5ba;">
                                        <i class="bi bi-geo-alt-fill me-1"></i> <?= htmlspecialchars($restaurant->getLocation()) ?>
                                    </p>

                                    <p class="card-text mb-4" style="font-size: 0.9rem; opacity: 0.9;">
                                        <?= htmlspecialchars($restaurant->getDescription()) ?>
                                    </p>

                                    <div class="mt-auto">
                                        <a href="/yummy/restaurant/<?= $restaurant->getId() ?>" class="more-btn">
                                            View Restaurant 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>