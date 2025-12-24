<h2 class="mb-3"><?= $isEdit ? 'Edit Ad' : 'Create Ad' ?></h2>
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
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="<?= e($values['title'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" required><?= e($values['description'] ?? '') ?></textarea>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Subject</label>
            <select name="subject_id" class="form-select" required>
                <option value="">Select subject</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= $subject['id'] ?>" <?= ($values['subject_id'] ?? '') == $subject['id'] ? 'selected' : '' ?>>
                        <?= e($subject['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">District</label>
            <select name="district_id" class="form-select" required>
                <option value="">Select district</option>
                <?php foreach ($districts as $district): ?>
                    <option value="<?= $district['id'] ?>" <?= ($values['district_id'] ?? '') == $district['id'] ? 'selected' : '' ?>>
                        <?= e($district['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Language</label>
            <select name="language" class="form-select" required>
                <option value="">Select language</option>
                <option value="si" <?= ($values['language'] ?? '') === 'si' ? 'selected' : '' ?>>Sinhala</option>
                <option value="en" <?= ($values['language'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                <option value="ta" <?= ($values['language'] ?? '') === 'ta' ? 'selected' : '' ?>>Tamil</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Class Type</label>
            <select name="class_type" class="form-select" required>
                <option value="">Select class type</option>
                <option value="online" <?= ($values['class_type'] ?? '') === 'online' ? 'selected' : '' ?>>Online</option>
                <option value="physical" <?= ($values['class_type'] ?? '') === 'physical' ? 'selected' : '' ?>>Physical</option>
                <option value="both" <?= ($values['class_type'] ?? '') === 'both' ? 'selected' : '' ?>>Online & Physical</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Fee (optional)</label>
            <input type="text" name="fee" class="form-control" value="<?= e($values['fee'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Schedule (optional)</label>
            <input type="text" name="schedule" class="form-control" value="<?= e($values['schedule'] ?? '') ?>">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Contact Info</label>
        <input type="text" name="contact_text" class="form-control" value="<?= e($values['contact_text'] ?? '') ?>" required>
    </div>
    <button class="btn btn-primary"><?= $isEdit ? 'Update Ad' : 'Create Ad' ?></button>
</form>
