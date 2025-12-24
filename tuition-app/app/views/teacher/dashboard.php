<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Total Ads</h5>
                <p class="display-6"><?= $totalAds ?></p>
                <a class="btn btn-primary" href="<?= BASE_URL ?>/teacher/ads">Manage Ads</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Profile</h5>
                <p>Update your bio, phone, and photo.</p>
                <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/teacher/profile/edit">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
