<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <?php if (!empty($teacher['photo_path'])): ?>
                    <img src="<?= BASE_URL ?>/<?= e($teacher['photo_path']) ?>" class="img-fluid rounded mb-3" alt="Teacher photo">
                <?php endif; ?>
                <h3><?= e($teacher['name']) ?></h3>
                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    <span class="rating-star"><i class="fas fa-star"></i></span>
                    <span><?= number_format((float)($rating['avg_rating'] ?? 0), 1) ?> (<?= (int)($rating['review_count'] ?? 0) ?> reviews)</span>
                </div>
                <p><?= nl2br(e($teacher['bio'] ?? '')) ?></p>
                <p><strong>Phone:</strong> <?= e($teacher['phone'] ?? 'Not shared') ?></p>
                <p><strong>WhatsApp:</strong> <?= e($teacher['whatsapp'] ?? 'Not shared') ?></p>
                <?php if (auth_user() && auth_user()['role'] === 'student'): ?>
                    <a class="btn btn-outline-primary" href="<?= BASE_URL ?>/teachers/<?= $teacher['id'] ?>/review">Write a review</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <h4>Approved Ads</h4>
        <div class="row g-3">
            <?php foreach ($ads as $ad): ?>
                <div class="col-md-6">
                    <?php require __DIR__ . '/../ads/partials/ad_card.php'; ?>
                </div>
            <?php endforeach; ?>
            <?php if (!$ads): ?>
                <p>No approved ads yet.</p>
            <?php endif; ?>
        </div>
        <div class="card mt-4 shadow-sm">
            <div class="card-body">
                <h4>Reviews</h4>
                <?php foreach ($reviews as $review): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <strong><?= e($review['reviewer_name']) ?></strong>
                        <span class="text-warning ms-2">
                            <?php for ($i = 0; $i < (int)$review['rating']; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </span>
                        <p class="mb-1"><?= nl2br(e($review['comment'])) ?></p>
                        <small class="text-muted"><?= format_date($review['created_at']) ?></small>
                    </div>
                <?php endforeach; ?>
                <?php if (!$reviews): ?>
                    <p>No reviews yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
