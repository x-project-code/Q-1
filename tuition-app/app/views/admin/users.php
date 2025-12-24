<h2 class="mb-3">Manage Users</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Joined</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= e($user['name']) ?></td>
                <td><?= e($user['email']) ?></td>
                <td><?= e($user['role']) ?></td>
                <td><?= e($user['status']) ?></td>
                <td><?= format_date($user['created_at']) ?></td>
                <td>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <?php if ($user['status'] === 'active'): ?>
                            <button class="btn btn-sm btn-warning" name="action" value="disabled">Disable</button>
                        <?php else: ?>
                            <button class="btn btn-sm btn-success" name="action" value="active">Enable</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
