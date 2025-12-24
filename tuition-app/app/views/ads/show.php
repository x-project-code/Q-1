<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2><?= e($ad['title']) ?></h2>
                <p class="text-muted">Posted on <?= format_date($ad['created_at']) ?></p>
                <p><?= nl2br(e($ad['description'])) ?></p>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Subject:</strong> <?= e($ad['subject_name']) ?></p>
                        <p><strong>District:</strong> <?= e($ad['district_name']) ?></p>
                        <p><strong>Language:</strong> <?= strtoupper(e($ad['language'])) ?></p>
                        <p><strong>Class Type:</strong> <?= ucfirst(e($ad['class_type'])) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fee:</strong> <?= $ad['fee'] ? e($ad['fee']) : 'Not specified' ?></p>
                        <p><strong>Schedule:</strong> <?= $ad['schedule'] ? e($ad['schedule']) : 'Not specified' ?></p>
                        <p><strong>Contact:</strong> <?= e($ad['contact_text']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4 shadow-sm">
            <div class="card-body">
                <h4>Teacher Reviews</h4>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="rating-star"><i class="fas fa-star"></i></span>
                    <strong><?= number_format((float)($rating['avg_rating'] ?? 0), 1) ?></strong>
                    <span class="text-muted">(<?= (int)($rating['review_count'] ?? 0) ?> reviews)</span>
                </div>
                <?php if ($reviews): ?>
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
                <?php else: ?>
                    <p>No reviews yet.</p>
                <?php endif; ?>
                <?php if (auth_user() && auth_user()['role'] === 'student'): ?>
                    <a class="btn btn-outline-primary" href="<?= BASE_URL ?>/teachers/<?= $ad['teacher_user_id'] ?>/review">Write a review</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <?php if (!empty($ad['photo_path'])): ?>
                    <img src="<?= BASE_URL ?>/<?= e($ad['photo_path']) ?>" class="img-fluid rounded mb-3" alt="Teacher">
                <?php endif; ?>
                <h4><?= e($ad['teacher_name']) ?></h4>
                <p><?= nl2br(e($ad['bio'] ?? '')) ?></p>
                <p><strong>Phone:</strong> <?= e($ad['phone'] ?? 'Not shared') ?></p>
                <p><strong>WhatsApp:</strong> <?= e($ad['whatsapp'] ?? 'Not shared') ?></p>
                <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/teachers/<?= $ad['teacher_user_id'] ?>">View Teacher Profile</a>
            </div>
        </div>
    </div>
</div>
