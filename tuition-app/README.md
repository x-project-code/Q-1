# Tuition Ads (PHP 8 + MySQL)

A simple, server-rendered PHP application for advertising private classes/tuition. Teachers can post ads, students/parents can search and filter listings, and reviews are available for teacher profiles.

## Features
- Public class ads with search, filters, and pagination
- Combined filters (district, subject, language, class type) + sorting
- Teacher profile pages with ratings and reviews
- Role-based access (Admin, Teacher, Student/Parent)
- Admin dashboard for moderation and master data
- CSRF protection, password hashing, and prepared statements

## Requirements
- PHP 8+
- MySQL/MariaDB (XAMPP or Laragon)

## Setup (XAMPP/Laragon)
1. Create a database and tables:
   ```bash
   mysql -u root -p < sql/schema.sql
   ```
   This creates the `tuition_app` database and seeds districts, subjects, and a default admin.

2. Update database credentials in `config/config.php` if needed:
   ```php
   const DB_HOST = '127.0.0.1';
   const DB_NAME = 'tuition_app';
   const DB_USER = 'root';
   const DB_PASS = '';
   ```

3. Point your web server document root to `tuition-app/public`.
   - XAMPP: `htdocs/tuition-app/public`
   - Laragon: `www/tuition-app/public`

4. Ensure `storage/uploads` is writable.

## Default Admin
- Email: `admin@example.com`
- Password: `change-me`

**Important:** Log in and change the password immediately.

## Routes
- Public: `/`, `/ads`, `/ads/{id}`, `/teachers/{id}`
- Auth: `/register`, `/login`, `/logout`
- Teacher: `/teacher/dashboard`, `/teacher/ads`, `/teacher/ads/create`, `/teacher/ads/{id}/edit`, `/teacher/profile/edit`
- Reviews: `/teachers/{id}/review`
- Admin: `/admin/login`, `/admin/dashboard`, `/admin/ads`, `/admin/reviews`, `/admin/users`, `/admin/subjects`, `/admin/districts`, `/admin/teachers`

## Notes
- Ads are visible publicly only when `status = 'approved'`.
- Teachers editing ads automatically move them back to `pending` for moderation.
- Reviews are limited to one per student/parent per teacher (enforced via unique constraint).
