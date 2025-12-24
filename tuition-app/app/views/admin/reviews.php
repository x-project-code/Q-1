<h2 class="mb-3">Reviews Moderation</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Teacher</th>
            <th>Reviewer</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Hidden</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?= e($review['teacher_name']) ?></td>
                <td><?= e($review['reviewer_name']) ?></td>
                <td><?= e((string)$review['rating']) ?></td>
                <td><?= e($review['comment']) ?></td>
                <td><?= $review['is_hidden'] ? 'Yes' : 'No' ?></td>
                <td>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                        <?php if ($review['is_hidden']): ?>
                            <button class="btn btn-sm btn-success" name="action" value="show">Show</button>
                        <?php else: ?>
                            <button class="btn btn-sm btn-warning" name="action" value="hide">Hide</button>
                        <?php endif; ?>
                        <button class="btn btn-sm btn-danger" name="action" value="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
