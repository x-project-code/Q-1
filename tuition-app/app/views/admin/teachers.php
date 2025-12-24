<h2 class="mb-3">Manage Teacher Profiles</h2>
<?php foreach ($teachers as $teacher): ?>
    <form method="post" class="card p-3 mb-3">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="teacher_id" value="<?= $teacher['id'] ?>">
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= e($teacher['name']) ?>" required>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= e($teacher['phone'] ?? '') ?>">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">WhatsApp</label>
                <input type="text" name="whatsapp" class="form-control" value="<?= e($teacher['whatsapp'] ?? '') ?>">
            </div>
        </div>
        <div class="mb-2">
            <label class="form-label">Bio</label>
            <textarea name="bio" class="form-control" rows="2"><?= e($teacher['bio'] ?? '') ?></textarea>
        </div>
        <button class="btn btn-dark">Save</button>
    </form>
<?php endforeach; ?>
