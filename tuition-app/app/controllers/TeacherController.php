<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Database;

class TeacherController
{
    public function dashboard(): void
    {
        require_auth();
        require_role('teacher');
        $user = auth_user();
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ads WHERE teacher_user_id = :id');
        $stmt->execute(['id' => $user['id']]);
        $totalAds = (int)$stmt->fetchColumn();

        view('teacher/dashboard', ['totalAds' => $totalAds]);
    }

    public function ads(): void
    {
        require_auth();
        require_role('teacher');
        $user = auth_user();
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT ads.*, subjects.name AS subject_name, districts.name AS district_name
            FROM ads
            JOIN subjects ON ads.subject_id = subjects.id
            JOIN districts ON ads.district_id = districts.id
            WHERE ads.teacher_user_id = :id
            ORDER BY ads.created_at DESC");
        $stmt->execute(['id' => $user['id']]);
        $ads = $stmt->fetchAll();
        view('teacher/ads', ['ads' => $ads]);
    }

    public function createAd(): void
    {
        require_auth();
        require_role('teacher');
        $pdo = Database::connection();
        $subjects = $pdo->query('SELECT * FROM subjects ORDER BY name')->fetchAll();
        $districts = $pdo->query('SELECT * FROM districts ORDER BY name')->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $data = $this->validateAdInput();
            if (!$data['errors']) {
                $stmt = $pdo->prepare("INSERT INTO ads (teacher_user_id, title, description, district_id, subject_id,
                    language, class_type, fee, schedule, contact_text, status, created_at, updated_at)
                    VALUES (:teacher_user_id, :title, :description, :district_id, :subject_id,
                    :language, :class_type, :fee, :schedule, :contact_text, 'pending', NOW(), NOW())");
                $stmt->execute([
                    'teacher_user_id' => auth_user()['id'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'district_id' => $data['district_id'],
                    'subject_id' => $data['subject_id'],
                    'language' => $data['language'],
                    'class_type' => $data['class_type'],
                    'fee' => $data['fee'],
                    'schedule' => $data['schedule'],
                    'contact_text' => $data['contact_text'],
                ]);
                redirect('teacher/ads');
            }
            view('teacher/ad_form', [
                'subjects' => $subjects,
                'districts' => $districts,
                'errors' => $data['errors'],
                'values' => $data,
                'isEdit' => false,
            ]);
            return;
        }

        view('teacher/ad_form', [
            'subjects' => $subjects,
            'districts' => $districts,
            'errors' => [],
            'values' => [],
            'isEdit' => false,
        ]);
    }

    public function editAd(int $id): void
    {
        require_auth();
        require_role('teacher');
        $pdo = Database::connection();
        $user = auth_user();
        $stmt = $pdo->prepare('SELECT * FROM ads WHERE id = :id AND teacher_user_id = :teacher_id');
        $stmt->execute(['id' => $id, 'teacher_id' => $user['id']]);
        $ad = $stmt->fetch();

        if (!$ad) {
            http_response_code(404);
            echo 'Ad not found.';
            return;
        }

        $subjects = $pdo->query('SELECT * FROM subjects ORDER BY name')->fetchAll();
        $districts = $pdo->query('SELECT * FROM districts ORDER BY name')->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $data = $this->validateAdInput();
            if (!$data['errors']) {
                $stmt = $pdo->prepare("UPDATE ads
                    SET title = :title, description = :description, district_id = :district_id, subject_id = :subject_id,
                        language = :language, class_type = :class_type, fee = :fee, schedule = :schedule,
                        contact_text = :contact_text, status = 'pending', updated_at = NOW()
                    WHERE id = :id AND teacher_user_id = :teacher_id");
                $stmt->execute([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'district_id' => $data['district_id'],
                    'subject_id' => $data['subject_id'],
                    'language' => $data['language'],
                    'class_type' => $data['class_type'],
                    'fee' => $data['fee'],
                    'schedule' => $data['schedule'],
                    'contact_text' => $data['contact_text'],
                    'id' => $id,
                    'teacher_id' => $user['id'],
                ]);
                redirect('teacher/ads');
            }
            view('teacher/ad_form', [
                'subjects' => $subjects,
                'districts' => $districts,
                'errors' => $data['errors'],
                'values' => array_merge($ad, $data),
                'isEdit' => true,
            ]);
            return;
        }

        view('teacher/ad_form', [
            'subjects' => $subjects,
            'districts' => $districts,
            'errors' => [],
            'values' => $ad,
            'isEdit' => true,
        ]);
    }

    public function editProfile(): void
    {
        require_auth();
        require_role('teacher');
        $pdo = Database::connection();
        $user = auth_user();

        $stmt = $pdo->prepare('SELECT * FROM teacher_profiles WHERE user_id = :id');
        $stmt->execute(['id' => $user['id']]);
        $profile = $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $bio = trim($_POST['bio'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $whatsapp = trim($_POST['whatsapp'] ?? '');
            $errors = [];

            if (strlen($bio) < 10) {
                $errors[] = 'Bio should be at least 10 characters.';
            }

            $photoPath = $profile['photo_path'] ?? null;
            if (!empty($_FILES['photo']['name'])) {
                $upload = $_FILES['photo'];
                if ($upload['size'] > 2 * 1024 * 1024) {
                    $errors[] = 'Photo must be under 2MB.';
                }
                $allowedTypes = ['image/jpeg', 'image/png'];
                if (!in_array(mime_content_type($upload['tmp_name']), $allowedTypes, true)) {
                    $errors[] = 'Only JPG or PNG files are allowed.';
                }
                if (!$errors) {
                    $filename = uniqid('teacher_', true) . '.' . pathinfo($upload['name'], PATHINFO_EXTENSION);
                    $destination = __DIR__ . '/../../public/storage/uploads/' . $filename;
                    move_uploaded_file($upload['tmp_name'], $destination);
                    $photoPath = 'storage/uploads/' . $filename;
                }
            }

            if (!$errors) {
                $stmt = $pdo->prepare('UPDATE teacher_profiles SET bio = :bio, phone = :phone, whatsapp = :whatsapp, photo_path = :photo_path WHERE user_id = :id');
                $stmt->execute([
                    'bio' => $bio,
                    'phone' => $phone,
                    'whatsapp' => $whatsapp,
                    'photo_path' => $photoPath,
                    'id' => $user['id'],
                ]);
                redirect('teacher/dashboard');
            }

            view('teacher/profile_edit', ['profile' => array_merge($profile, [
                'bio' => $bio,
                'phone' => $phone,
                'whatsapp' => $whatsapp,
                'photo_path' => $photoPath,
            ]), 'errors' => $errors]);
            return;
        }

        view('teacher/profile_edit', ['profile' => $profile, 'errors' => []]);
    }

    public function profile(int $id): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT users.id, users.name, teacher_profiles.bio, teacher_profiles.phone,
                teacher_profiles.whatsapp, teacher_profiles.photo_path
            FROM users
            LEFT JOIN teacher_profiles ON teacher_profiles.user_id = users.id
            WHERE users.id = :id AND users.role = 'teacher' AND users.status = 'active'");
        $stmt->execute(['id' => $id]);
        $teacher = $stmt->fetch();

        if (!$teacher) {
            http_response_code(404);
            echo 'Teacher not found.';
            return;
        }

        $adsStmt = $pdo->prepare("SELECT ads.*, subjects.name AS subject_name, districts.name AS district_name
            FROM ads
            JOIN subjects ON ads.subject_id = subjects.id
            JOIN districts ON ads.district_id = districts.id
            WHERE ads.teacher_user_id = :id AND ads.status = 'approved'
            ORDER BY ads.created_at DESC");
        $adsStmt->execute(['id' => $id]);
        $ads = $adsStmt->fetchAll();

        $reviewsStmt = $pdo->prepare("SELECT reviews.*, users.name AS reviewer_name
            FROM reviews
            JOIN users ON reviews.reviewer_user_id = users.id
            WHERE reviews.teacher_user_id = :id AND reviews.is_hidden = 0
            ORDER BY reviews.created_at DESC");
        $reviewsStmt->execute(['id' => $id]);
        $reviews = $reviewsStmt->fetchAll();

        $ratingStmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count
            FROM reviews WHERE teacher_user_id = :id AND is_hidden = 0");
        $ratingStmt->execute(['id' => $id]);
        $rating = $ratingStmt->fetch();

        view('teachers/profile', [
            'teacher' => $teacher,
            'ads' => $ads,
            'reviews' => $reviews,
            'rating' => $rating,
        ]);
    }

    private function validateAdInput(): array
    {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $district_id = (int)($_POST['district_id'] ?? 0);
        $subject_id = (int)($_POST['subject_id'] ?? 0);
        $language = $_POST['language'] ?? '';
        $class_type = $_POST['class_type'] ?? '';
        $fee = trim($_POST['fee'] ?? '');
        $schedule = trim($_POST['schedule'] ?? '');
        $contact_text = trim($_POST['contact_text'] ?? '');

        $errors = [];
        if (strlen($title) < 5) {
            $errors[] = 'Title must be at least 5 characters.';
        }
        if (strlen($description) < 20) {
            $errors[] = 'Description must be at least 20 characters.';
        }
        if ($district_id <= 0) {
            $errors[] = 'District is required.';
        }
        if ($subject_id <= 0) {
            $errors[] = 'Subject is required.';
        }
        if (!in_array($language, ['si', 'en', 'ta'], true)) {
            $errors[] = 'Language is required.';
        }
        if (!in_array($class_type, ['online', 'physical', 'both'], true)) {
            $errors[] = 'Class type is required.';
        }
        if (strlen($contact_text) < 5) {
            $errors[] = 'Contact info is required.';
        }

        return [
            'title' => $title,
            'description' => $description,
            'district_id' => $district_id,
            'subject_id' => $subject_id,
            'language' => $language,
            'class_type' => $class_type,
            'fee' => $fee ?: null,
            'schedule' => $schedule ?: null,
            'contact_text' => $contact_text,
            'errors' => $errors,
        ];
    }
}
