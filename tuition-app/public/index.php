<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/models/Database.php';
require __DIR__ . '/../app/controllers/AuthController.php';
require __DIR__ . '/../app/controllers/AdsController.php';
require __DIR__ . '/../app/controllers/TeacherController.php';
require __DIR__ . '/../app/controllers/ReviewController.php';
require __DIR__ . '/../app/controllers/AdminController.php';

use App\Controllers\AuthController;
use App\Controllers\AdsController;
use App\Controllers\TeacherController;
use App\Controllers\ReviewController;
use App\Controllers\AdminController;

$route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Remove base path if app is in a subfolder
$basePath = trim(str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($basePath && str_starts_with($route, $basePath)) {
    $route = trim(substr($route, strlen($basePath)), '/');
}

if ($route === '') {
    (new AdsController())->home();
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch (true) {
    case $route === 'ads':
        (new AdsController())->index();
        break;
    case preg_match('#^ads/(\d+)$#', $route, $matches):
        (new AdsController())->show((int)$matches[1]);
        break;
    case $route === 'register':
        (new AuthController())->register();
        break;
    case $route === 'login':
        (new AuthController())->login();
        break;
    case $route === 'logout':
        (new AuthController())->logout();
        break;
    case $route === 'teacher/dashboard':
        (new TeacherController())->dashboard();
        break;
    case $route === 'teacher/ads':
        (new TeacherController())->ads();
        break;
    case $route === 'teacher/ads/create':
        (new TeacherController())->createAd();
        break;
    case preg_match('#^teacher/ads/(\d+)/edit$#', $route, $matches):
        (new TeacherController())->editAd((int)$matches[1]);
        break;
    case $route === 'teacher/profile/edit':
        (new TeacherController())->editProfile();
        break;
    case preg_match('#^teachers/(\d+)$#', $route, $matches):
        (new TeacherController())->profile((int)$matches[1]);
        break;
    case preg_match('#^teachers/(\d+)/review$#', $route, $matches):
        (new ReviewController())->review((int)$matches[1]);
        break;
    case $route === 'admin/login':
        (new AdminController())->login();
        break;
    case $route === 'admin/dashboard':
        (new AdminController())->dashboard();
        break;
    case $route === 'admin/ads':
        (new AdminController())->ads();
        break;
    case $route === 'admin/reviews':
        (new AdminController())->reviews();
        break;
    case $route === 'admin/users':
        (new AdminController())->users();
        break;
    case $route === 'admin/teachers':
        (new AdminController())->teachers();
        break;
    case $route === 'admin/subjects':
        (new AdminController())->subjects();
        break;
    case $route === 'admin/districts':
        (new AdminController())->districts();
        break;
    default:
        http_response_code(404);
        echo 'Page not found';
        break;
}
