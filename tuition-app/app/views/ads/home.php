<div class="hero mb-4">
    <h1 class="mb-3">Find the perfect private class</h1>
    <p class="mb-4">Search by subject, district, language and class type to connect with trusted teachers.</p>
    <?php require __DIR__ . '/partials/filter_form.php'; ?>
</div>

<h3 class="mb-3">Latest Approved Ads</h3>
<div class="row g-4">
    <?php foreach ($ads as $ad): ?>
        <div class="col-md-4">
            <?php require __DIR__ . '/partials/ad_card.php'; ?>
        </div>
    <?php endforeach; ?>
    <?php if (!$ads): ?>
        <p>No ads available yet.</p>
    <?php endif; ?>
</div>
