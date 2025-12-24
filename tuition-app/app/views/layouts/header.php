<?php
$user = auth_user();
$isAdmin = $user && $user['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tuition Ads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f5f6fa; }
        .rating-star { color: #f1c40f; }
        .hero { background: linear-gradient(120deg, #1f6feb, #6f42c1); color: #fff; padding: 40px; border-radius: 12px; }
        .card { border-radius: 12px; }
        .admin-shell { background: #f4f6f9; }
    </style>
</head>
<body class="<?= $isAdmin ? 'admin-shell' : '' ?>">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/">Tuition Ads</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/ads">Browse Ads</a></li>
                <?php if ($user && $user['role'] === 'teacher'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/teacher/dashboard">Teacher Dashboard</a></li>
                <?php endif; ?>
                <?php if ($user && $user['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard">Admin</a></li>
                <?php endif; ?>
                <?php if ($user): ?>
                    <li class="nav-item"><span class="nav-link">Hi, <?= e($user['name']) ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container my-4">
