<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Database;

class ReviewController
{
    public function review(int $teacherId): void
    {
        require_auth();
        $user = auth_user();
        if ($user['role'] !== 'student') {
            http_response_code(403);
            echo 'Only students/parents can review.';
            return;
        }

        $pdo = Database::connection();
        $teacherStmt = $pdo->prepare("SELECT id, name FROM users WHERE id = :id AND role = 'teacher'");
        $teacherStmt->execute(['id' => $teacherId]);
        $teacher = $teacherStmt->fetch();
        if (!$teacher) {
            http_response_code(404);
            echo 'Teacher not found.';
            return;
        }

        $reviewStmt = $pdo->prepare('SELECT * FROM reviews WHERE teacher_user_id = :teacher_id AND reviewer_user_id = :user_id');
        $reviewStmt->execute(['teacher_id' => $teacherId, 'user_id' => $user['id']]);
        $existing = $reviewStmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $rating = (int)($_POST['rating'] ?? 0);
            $comment = trim($_POST['comment'] ?? '');

            $errors = [];
            if ($rating < 1 || $rating > 5) {
                $errors[] = 'Rating must be between 1 and 5.';
            }
            if (strlen($comment) < 10 || strlen($comment) > 500) {
                $errors[] = 'Comment must be between 10 and 500 characters.';
            }
            if (stripos($comment, 'badword') !== false) {
                $errors[] = 'Please remove inappropriate language.';
            }

            if (!$errors) {
                if ($existing) {
                    $stmt = $pdo->prepare('UPDATE reviews SET rating = :rating, comment = :comment, updated_at = NOW(), is_hidden = 0
                        WHERE id = :id');
                    $stmt->execute([
                        'rating' => $rating,
                        'comment' => $comment,
                        'id' => $existing['id'],
                    ]);
                } else {
                    $stmt = $pdo->prepare('INSERT INTO reviews (teacher_user_id, reviewer_user_id, rating, comment, is_hidden, created_at, updated_at)
                        VALUES (:teacher_id, :user_id, :rating, :comment, 0, NOW(), NOW())');
                    $stmt->execute([
                        'teacher_id' => $teacherId,
                        'user_id' => $user['id'],
                        'rating' => $rating,
                        'comment' => $comment,
                    ]);
                }
                redirect('teachers/' . $teacherId);
            }

            view('teachers/review_form', [
                'teacher' => $teacher,
                'review' => ['rating' => $rating, 'comment' => $comment],
                'errors' => $errors,
            ]);
            return;
        }

        view('teachers/review_form', [
            'teacher' => $teacher,
            'review' => $existing ?: ['rating' => 5, 'comment' => ''],
            'errors' => [],
        ]);
    }
}
