<h2 class="mb-3">Ads Moderation</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>Teacher</th>
            <th>Subject</th>
            <th>District</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ads as $ad): ?>
            <tr>
                <td><?= e($ad['title']) ?></td>
                <td><?= e($ad['teacher_name']) ?></td>
                <td><?= e($ad['subject_name']) ?></td>
                <td><?= e($ad['district_name']) ?></td>
                <td><?= e($ad['status']) ?></td>
                <td>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="ad_id" value="<?= $ad['id'] ?>">
                        <button class="btn btn-sm btn-success" name="action" value="approved">Approve</button>
                        <button class="btn btn-sm btn-warning" name="action" value="rejected">Reject</button>
                        <button class="btn btn-sm btn-danger" name="action" value="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
