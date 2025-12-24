<h2 class="mb-3">Edit Teacher Profile</h2>
<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="mb-3">
        <label class="form-label">Bio</label>
        <textarea name="bio" class="form-control" rows="4" required><?= e($profile['bio'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= e($profile['phone'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">WhatsApp</label>
        <input type="text" name="whatsapp" class="form-control" value="<?= e($profile['whatsapp'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Photo (JPG/PNG, max 2MB)</label>
        <input type="file" name="photo" class="form-control">
        <?php if (!empty($profile['photo_path'])): ?>
            <img src="<?= BASE_URL ?>/<?= e($profile['photo_path']) ?>" class="img-thumbnail mt-2" style="max-width: 160px;" alt="Teacher photo">
        <?php endif; ?>
    </div>
    <button class="btn btn-primary">Save Profile</button>
</form>
