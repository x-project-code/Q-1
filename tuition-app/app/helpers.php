<?php
declare(strict_types=1);

function view(string $path, array $data = []): void
{
    extract($data);
    require __DIR__ . '/views/layouts/header.php';
    require __DIR__ . '/views/' . $path . '.php';
    require __DIR__ . '/views/layouts/footer.php';
}

function redirect(string $path): void
{
    header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(400);
        exit('Invalid CSRF token');
    }
}

function auth_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_auth(): void
{
    if (!auth_user()) {
        redirect('login');
    }
}

function require_role(string $role): void
{
    $user = auth_user();
    if (!$user || $user['role'] !== $role) {
        http_response_code(403);
        exit('Forbidden');
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function format_date(string $date): string
{
    return date('M d, Y', strtotime($date));
}
