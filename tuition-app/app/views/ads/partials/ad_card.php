<div class="card shadow-sm h-100">
    <div class="card-body">
        <h5 class="card-title mb-1"><?= e($ad['title']) ?></h5>
        <p class="text-muted mb-2">by <?= e($ad['teacher_name']) ?></p>
        <p class="mb-1"><strong>Subject:</strong> <?= e($ad['subject_name']) ?></p>
        <p class="mb-1"><strong>District:</strong> <?= e($ad['district_name']) ?></p>
        <p class="mb-1"><strong>Language:</strong> <?= strtoupper(e($ad['language'])) ?></p>
        <p class="mb-1"><strong>Class Type:</strong> <?= ucfirst(e($ad['class_type'])) ?></p>
        <p class="mb-2"><strong>Fee:</strong> <?= $ad['fee'] ? e($ad['fee']) : 'Not specified' ?></p>
        <div class="d-flex align-items-center gap-2">
            <span class="rating-star"><i class="fas fa-star"></i></span>
            <span><?= number_format((float)($ad['avg_rating'] ?? 0), 1) ?> (<?= (int)($ad['review_count'] ?? 0) ?> reviews)</span>
        </div>
    </div>
    <div class="card-footer bg-white border-0">
        <a href="<?= BASE_URL ?>/ads/<?= $ad['id'] ?>" class="btn btn-primary w-100">View Details</a>
    </div>
</div>
