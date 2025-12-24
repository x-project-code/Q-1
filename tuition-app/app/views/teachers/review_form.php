<h2 class="mb-3">Review <?= e($teacher['name']) ?></h2>
<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form method="post" class="card p-4 shadow-sm">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="mb-3">
        <label class="form-label">Rating</label>
        <select name="rating" class="form-select" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>" <?= (int)$review['rating'] === $i ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Comment</label>
        <textarea name="comment" class="form-control" rows="4" required><?= e($review['comment'] ?? '') ?></textarea>
        <small class="text-muted">Min 10, max 500 characters.</small>
    </div>
    <button class="btn btn-primary">Submit Review</button>
</form>
