<form method="get" action="<?= BASE_URL ?>/ads" class="row g-3 align-items-end">
    <div class="col-md-4">
        <label class="form-label">Search</label>
        <input type="text" name="search" value="<?= e($filters['search']) ?>" class="form-control" placeholder="Keyword search">
    </div>
    <div class="col-md-2">
        <label class="form-label">Subject</label>
        <select name="subject_id" class="form-select">
            <option value="">All</option>
            <?php foreach ($subjects as $subject): ?>
                <option value="<?= $subject['id'] ?>" <?= $filters['subject_id'] == $subject['id'] ? 'selected' : '' ?>>
                    <?= e($subject['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">District</label>
        <select name="district_id" class="form-select">
            <option value="">All</option>
            <?php foreach ($districts as $district): ?>
                <option value="<?= $district['id'] ?>" <?= $filters['district_id'] == $district['id'] ? 'selected' : '' ?>>
                    <?= e($district['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Language</label>
        <select name="language" class="form-select">
            <option value="">All</option>
            <option value="si" <?= $filters['language'] === 'si' ? 'selected' : '' ?>>Sinhala</option>
            <option value="en" <?= $filters['language'] === 'en' ? 'selected' : '' ?>>English</option>
            <option value="ta" <?= $filters['language'] === 'ta' ? 'selected' : '' ?>>Tamil</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Class Type</label>
        <select name="class_type" class="form-select">
            <option value="">All</option>
            <option value="online" <?= $filters['class_type'] === 'online' ? 'selected' : '' ?>>Online</option>
            <option value="physical" <?= $filters['class_type'] === 'physical' ? 'selected' : '' ?>>Physical</option>
            <option value="both" <?= $filters['class_type'] === 'both' ? 'selected' : '' ?>>Online & Physical</option>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">Sort</label>
        <select name="sort" class="form-select">
            <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Newest</option>
            <option value="highest_rated" <?= $filters['sort'] === 'highest_rated' ? 'selected' : '' ?>>Highest Rated</option>
            <option value="most_reviewed" <?= $filters['sort'] === 'most_reviewed' ? 'selected' : '' ?>>Most Reviewed</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-light w-100" type="submit">Search</button>
    </div>
</form>
