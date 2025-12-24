<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Database;

class AdminController
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $pdo = Database::connection();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email AND role = "admin" AND status = "active"');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ];
                redirect('admin/dashboard');
            }
            view('admin/login', ['error' => 'Invalid admin credentials.']);
            return;
        }
        view('admin/login', ['error' => null]);
    }

    public function dashboard(): void
    {
        $this->requireAdmin();
        $pdo = Database::connection();
        $stats = [
            'ads' => (int)$pdo->query('SELECT COUNT(*) FROM ads')->fetchColumn(),
            'pending_ads' => (int)$pdo->query("SELECT COUNT(*) FROM ads WHERE status = 'pending'")->fetchColumn(),
            'teachers' => (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'teacher'")->fetchColumn(),
            'reviews' => (int)$pdo->query('SELECT COUNT(*) FROM reviews')->fetchColumn(),
        ];
        view('admin/dashboard', ['stats' => $stats]);
    }

    public function ads(): void
    {
        $this->requireAdmin();
        $pdo = Database::connection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $action = $_POST['action'] ?? '';
            $adId = (int)($_POST['ad_id'] ?? 0);
            if ($adId > 0 && in_array($action, ['approved', 'rejected', 'delete'], true)) {
                if ($action === 'delete') {
                    $stmt = $pdo->prepare('DELETE FROM ads WHERE id = :id');
                    $stmt->execute(['id' => $adId]);
                } else {
                    $stmt = $pdo->prepare('UPDATE ads SET status = :status WHERE id = :id');
                    $stmt->execute(['status' => $action, 'id' => $adId]);
                }
            }
            redirect('admin/ads');
        }

        $stmt = $pdo->query("SELECT ads.*, users.name AS teacher_name, subjects.name AS subject_name, districts.name AS district_name
            FROM ads
            JOIN users ON ads.teacher_user_id = users.id
            JOIN subjects ON ads.subject_id = subjects.id
            JOIN districts ON ads.district_id = districts.id
            ORDER BY ads.created_at DESC");
        $ads = $stmt->fetchAll();
        view('admin/ads', ['ads' => $ads]);
    }

    public function reviews(): void
    {
        $this->requireAdmin();
        $pdo = Database::connection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $reviewId = (int)($_POST['review_id'] ?? 0);
            $action = $_POST['action'] ?? '';
            if ($reviewId > 0) {
                if ($action === 'delete') {
                    $stmt = $pdo->prepare('DELETE FROM reviews WHERE id = :id');
                    $stmt->execute(['id' => $reviewId]);
                } elseif (in_array($action, ['hide', 'show'], true)) {
                    $stmt = $pdo->prepare('UPDATE reviews SET is_hidden = :hidden WHERE id = :id');
                    $stmt->execute(['hidden' => $action === 'hide' ? 1 : 0, 'id' => $reviewId]);
                }
            }
            redirect('admin/reviews');
        }

        $stmt = $pdo->query("SELECT reviews.*, teachers.name AS teacher_name, reviewers.name AS reviewer_name
            FROM reviews
            JOIN users AS teachers ON reviews.teacher_user_id = teachers.id
            JOIN users AS reviewers ON reviews.reviewer_user_id = reviewers.id
            ORDER BY reviews.created_at DESC");
        $reviews = $stmt->fetchAll();
        view('admin/reviews', ['reviews' => $reviews]);
    }

    public function users(): void
    {
        $this->requireAdmin();
        $pdo = Database::connection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $userId = (int)($_POST['user_id'] ?? 0);
            $action = $_POST['action'] ?? '';
            if ($userId > 0 && in_array($action, ['active', 'disabled'], true)) {
                $stmt = $pdo->prepare('UPDATE users SET status = :status WHERE id = :id');
                $stmt->execute(['status' => $action, 'id' => $userId]);
            }
            redirect('admin/users');
        }

        $users = $pdo->query('SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC')->fetchAll();
        view('admin/users', ['users' => $users]);
    }

    public function teachers(): void
    {
        $this->requireAdmin();
        $pdo = Database::connection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $teacherId = (int)($_POST['teacher_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $whatsapp = trim($_POST['whatsapp'] ?? '');

            if ($teacherId > 0 && $name) {
                $stmt = $pdo->prepare('UPDATE users SET name = :name WHERE id = :id AND role = \"teacher\"');
                $stmt->execute(['name' => $name, 'id' => $teacherId]);
                $stmt = $pdo->prepare('UPDATE teacher_profiles SET bio = :bio, phone = :phone, whatsapp = :whatsapp WHERE user_id = :id');
                $stmt->execute([
                    'bio' => $bio,
                    'phone' => $phone,
                    'whatsapp' => $whatsapp,
                    'id' => $teacherId,
                ]);
            }
            redirect('admin/teachers');
        }

        $stmt = $pdo->query(\"SELECT users.id, users.name, users.email, teacher_profiles.bio, teacher_profiles.phone, teacher_profiles.whatsapp\n            FROM users\n            LEFT JOIN teacher_profiles ON teacher_profiles.user_id = users.id\n            WHERE users.role = 'teacher'\n            ORDER BY users.created_at DESC\");\n        $teachers = $stmt->fetchAll();
        view('admin/teachers', ['teachers' => $teachers]);
    }

    public function subjects(): void
    {
        $this->requireAdmin();
        $pdo = Database::connection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $name = trim($_POST['name'] ?? '');
            $deleteId = (int)($_POST['delete_id'] ?? 0);
            if ($deleteId > 0) {
                $stmt = $pdo->prepare('DELETE FROM subjects WHERE id = :id');
                $stmt->execute(['id' => $deleteId]);
            } elseif ($name) {
                $stmt = $pdo->prepare('INSERT INTO subjects (name) VALUES (:name)');
                $stmt->execute(['name' => $name]);
            }
            redirect('admin/subjects');
        }

        $subjects = $pdo->query('SELECT * FROM subjects ORDER BY name')->fetchAll();
        view('admin/subjects', ['subjects' => $subjects]);
    }

    public function districts(): void
    {
        $this->requireAdmin();
        $pdo = Database::connection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $name = trim($_POST['name'] ?? '');
            $deleteId = (int)($_POST['delete_id'] ?? 0);
            if ($deleteId > 0) {
                $stmt = $pdo->prepare('DELETE FROM districts WHERE id = :id');
                $stmt->execute(['id' => $deleteId]);
            } elseif ($name) {
                $stmt = $pdo->prepare('INSERT INTO districts (name) VALUES (:name)');
                $stmt->execute(['name' => $name]);
            }
            redirect('admin/districts');
        }

        $districts = $pdo->query('SELECT * FROM districts ORDER BY name')->fetchAll();
        view('admin/districts', ['districts' => $districts]);
    }

    private function requireAdmin(): void
    {
        require_auth();
        require_role('admin');
    }
}
