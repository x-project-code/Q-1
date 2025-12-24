<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Database;

class AdsController
{
    public function home(): void
    {
        $filters = $this->getFilters();
        $latestAds = $this->getAds($filters, 1, 6);
        $subjects = $this->getSubjects();
        $districts = $this->getDistricts();
        view('ads/home', [
            'ads' => $latestAds,
            'subjects' => $subjects,
            'districts' => $districts,
            'filters' => $filters,
        ]);
    }

    public function index(): void
    {
        $filters = $this->getFilters();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 8;
        $ads = $this->getAds($filters, $page, $perPage);
        $total = $this->countAds($filters);
        $subjects = $this->getSubjects();
        $districts = $this->getDistricts();
        $totalPages = (int)ceil($total / $perPage);

        view('ads/index', [
            'ads' => $ads,
            'subjects' => $subjects,
            'districts' => $districts,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    public function show(int $id): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT ads.*, subjects.name AS subject_name, districts.name AS district_name,
                users.name AS teacher_name, teacher_profiles.bio, teacher_profiles.phone, teacher_profiles.whatsapp,
                teacher_profiles.photo_path
            FROM ads
            JOIN users ON ads.teacher_user_id = users.id
            LEFT JOIN teacher_profiles ON teacher_profiles.user_id = users.id
            JOIN subjects ON ads.subject_id = subjects.id
            JOIN districts ON ads.district_id = districts.id
            WHERE ads.id = :id AND ads.status = 'approved'");
        $stmt->execute(['id' => $id]);
        $ad = $stmt->fetch();

        if (!$ad) {
            http_response_code(404);
            echo 'Ad not found';
            return;
        }

        $reviewStmt = $pdo->prepare("SELECT reviews.*, users.name AS reviewer_name
            FROM reviews
            JOIN users ON reviews.reviewer_user_id = users.id
            WHERE reviews.teacher_user_id = :teacher_id AND reviews.is_hidden = 0
            ORDER BY reviews.created_at DESC");
        $reviewStmt->execute(['teacher_id' => $ad['teacher_user_id']]);
        $reviews = $reviewStmt->fetchAll();

        $ratingStmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count
            FROM reviews
            WHERE teacher_user_id = :teacher_id AND is_hidden = 0");
        $ratingStmt->execute(['teacher_id' => $ad['teacher_user_id']]);
        $rating = $ratingStmt->fetch();

        view('ads/show', [
            'ad' => $ad,
            'reviews' => $reviews,
            'rating' => $rating,
        ]);
    }

    private function getFilters(): array
    {
        return [
            'search' => trim($_GET['search'] ?? ''),
            'subject_id' => $_GET['subject_id'] ?? '',
            'district_id' => $_GET['district_id'] ?? '',
            'language' => $_GET['language'] ?? '',
            'class_type' => $_GET['class_type'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest',
        ];
    }

    private function baseQuery(array $filters): array
    {
        $where = ["ads.status = 'approved'"];
        $params = [];

        if ($filters['search']) {
            $where[] = "(ads.title LIKE :search OR users.name LIKE :search OR subjects.name LIKE :search OR districts.name LIKE :search OR ads.description LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        if ($filters['subject_id']) {
            $where[] = 'ads.subject_id = :subject_id';
            $params['subject_id'] = $filters['subject_id'];
        }
        if ($filters['district_id']) {
            $where[] = 'ads.district_id = :district_id';
            $params['district_id'] = $filters['district_id'];
        }
        if ($filters['language']) {
            $where[] = 'ads.language = :language';
            $params['language'] = $filters['language'];
        }
        if ($filters['class_type']) {
            $where[] = 'ads.class_type = :class_type';
            $params['class_type'] = $filters['class_type'];
        }

        return [$where, $params];
    }

    private function getAds(array $filters, int $page, int $perPage): array
    {
        [$where, $params] = $this->baseQuery($filters);
        $orderBy = match ($filters['sort']) {
            'highest_rated' => 'rating_summary.avg_rating DESC, rating_summary.review_count DESC',
            'most_reviewed' => 'rating_summary.review_count DESC, rating_summary.avg_rating DESC',
            default => 'ads.created_at DESC',
        };

        $offset = ($page - 1) * $perPage;
        $pdo = Database::connection();
        $sql = "SELECT ads.*, subjects.name AS subject_name, districts.name AS district_name,
                users.name AS teacher_name, rating_summary.avg_rating, rating_summary.review_count
            FROM ads
            JOIN users ON ads.teacher_user_id = users.id
            JOIN subjects ON ads.subject_id = subjects.id
            JOIN districts ON ads.district_id = districts.id
            LEFT JOIN (
                SELECT teacher_user_id, AVG(rating) AS avg_rating, COUNT(*) AS review_count
                FROM reviews
                WHERE is_hidden = 0
                GROUP BY teacher_user_id
            ) AS rating_summary ON rating_summary.teacher_user_id = ads.teacher_user_id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function countAds(array $filters): int
    {
        [$where, $params] = $this->baseQuery($filters);
        $pdo = Database::connection();
        $sql = "SELECT COUNT(*) AS total
            FROM ads
            JOIN users ON ads.teacher_user_id = users.id
            JOIN subjects ON ads.subject_id = subjects.id
            JOIN districts ON ads.district_id = districts.id
            WHERE " . implode(' AND ', $where);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    private function getSubjects(): array
    {
        $pdo = Database::connection();
        return $pdo->query('SELECT * FROM subjects ORDER BY name')->fetchAll();
    }

    private function getDistricts(): array
    {
        $pdo = Database::connection();
        return $pdo->query('SELECT * FROM districts ORDER BY name')->fetchAll();
    }
}
