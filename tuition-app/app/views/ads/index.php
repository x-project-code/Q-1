<h2 class="mb-3">Browse Class Ads</h2>
<?php require __DIR__ . '/partials/filter_form.php'; ?>

<div class="row g-4 mt-1">
    <?php foreach ($ads as $ad): ?>
        <div class="col-md-6 col-lg-4">
            <?php require __DIR__ . '/partials/ad_card.php'; ?>
        </div>
    <?php endforeach; ?>
    <?php if (!$ads): ?>
        <p class="mt-3">No ads match your filters.</p>
    <?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="<?= BASE_URL ?>/ads?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
