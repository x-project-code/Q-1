<h2 class="mb-3">Subjects</h2>
<form method="post" class="card p-3 mb-3">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="row align-items-end">
        <div class="col-md-6">
            <label class="form-label">New Subject</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Add Subject</button>
        </div>
    </div>
</form>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($subjects as $subject): ?>
            <tr>
                <td><?= e($subject['name']) ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="delete_id" value="<?= $subject['id'] ?>">
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
