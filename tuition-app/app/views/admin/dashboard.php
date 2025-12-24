<section class="content">
    <div class="container-fluid">
        <h2 class="mb-4">Admin Dashboard</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $stats['ads'] ?></h3>
                        <p>Total Ads</p>
                    </div>
                    <div class="icon"><i class="fas fa-bullhorn"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $stats['pending_ads'] ?></h3>
                        <p>Pending Ads</p>
                    </div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $stats['teachers'] ?></h3>
                        <p>Total Teachers</p>
                    </div>
                    <div class="icon"><i class="fas fa-user"></i></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $stats['reviews'] ?></h3>
                        <p>Total Reviews</p>
                    </div>
                    <div class="icon"><i class="fas fa-star"></i></div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <a class="btn btn-outline-dark me-2" href="<?= BASE_URL ?>/admin/ads">Manage Ads</a>
            <a class="btn btn-outline-dark me-2" href="<?= BASE_URL ?>/admin/reviews">Manage Reviews</a>
            <a class="btn btn-outline-dark me-2" href="<?= BASE_URL ?>/admin/users">Manage Users</a>
            <a class="btn btn-outline-dark me-2" href="<?= BASE_URL ?>/admin/teachers">Teacher Profiles</a>
            <a class="btn btn-outline-dark me-2" href="<?= BASE_URL ?>/admin/subjects">Subjects</a>
            <a class="btn btn-outline-dark" href="<?= BASE_URL ?>/admin/districts">Districts</a>
        </div>
    </div>
</section>
