<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Database;

class AuthController
{
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'student';

            $errors = [];
            if (strlen($name) < 3) {
                $errors[] = 'Name is required.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Valid email is required.';
            }
            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters.';
            }
            if (!in_array($role, ['teacher', 'student'], true)) {
                $errors[] = 'Invalid role.';
            }

            if (!$errors) {
                $pdo = Database::connection();
                $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
                $stmt->execute(['email' => $email]);
                if ($stmt->fetch()) {
                    $errors[] = 'Email already registered.';
                } else {
                    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, status, created_at)
                        VALUES (:name, :email, :password_hash, :role, :status, NOW())');
                    $stmt->execute([
                        'name' => $name,
                        'email' => $email,
                        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                        'role' => $role,
                        'status' => 'active',
                    ]);
                    $userId = (int)$pdo->lastInsertId();

                    if ($role === 'teacher') {
                        $profileStmt = $pdo->prepare('INSERT INTO teacher_profiles (user_id, bio, phone, whatsapp, photo_path, created_at)
                            VALUES (:user_id, :bio, :phone, :whatsapp, :photo_path, NOW())');
                        $profileStmt->execute([
                            'user_id' => $userId,
                            'bio' => '',
                            'phone' => '',
                            'whatsapp' => '',
                            'photo_path' => null,
                        ]);
                    }

                    $_SESSION['user'] = [
                        'id' => $userId,
                        'name' => $name,
                        'email' => $email,
                        'role' => $role,
                    ];
                    redirect($role === 'teacher' ? 'teacher/dashboard' : 'ads');
                }
            }

            view('auth/register', ['errors' => $errors]);
            return;
        }

        view('auth/register', ['errors' => []]);
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $pdo = Database::connection();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email AND status = "active"');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ];
                if ($user['role'] === 'admin') {
                    redirect('admin/dashboard');
                }
                if ($user['role'] === 'teacher') {
                    redirect('teacher/dashboard');
                }
                redirect('ads');
            }

            view('auth/login', ['error' => 'Invalid credentials.']);
            return;
        }

        view('auth/login', ['error' => null]);
    }

    public function logout(): void
    {
        session_destroy();
        redirect('login');
    }
}
