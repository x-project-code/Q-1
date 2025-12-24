<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>My Ads</h2>
    <a class="btn btn-primary" href="<?= BASE_URL ?>/teacher/ads/create">Create New Ad</a>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>Subject</th>
            <th>District</th>
            <th>Status</th>
            <th>Created</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ads as $ad): ?>
            <tr>
                <td><?= e($ad['title']) ?></td>
                <td><?= e($ad['subject_name']) ?></td>
                <td><?= e($ad['district_name']) ?></td>
                <td><?= ucfirst(e($ad['status'])) ?></td>
                <td><?= format_date($ad['created_at']) ?></td>
                <td><a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>/teacher/ads/<?= $ad['id'] ?>/edit">Edit</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$ads): ?>
            <tr><td colspan="6">No ads yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
