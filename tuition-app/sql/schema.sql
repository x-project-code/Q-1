CREATE DATABASE IF NOT EXISTS tuition_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tuition_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','teacher','student') NOT NULL,
    status ENUM('active','disabled') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL
);

CREATE TABLE teacher_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    bio TEXT,
    phone VARCHAR(50),
    whatsapp VARCHAR(50),
    photo_path VARCHAR(255),
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_teacher_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE districts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE ads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_user_id INT NOT NULL,
    title VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    district_id INT NOT NULL,
    subject_id INT NOT NULL,
    language ENUM('si','en','ta') NOT NULL,
    class_type ENUM('online','physical','both') NOT NULL,
    fee VARCHAR(80),
    schedule VARCHAR(160),
    contact_text VARCHAR(200) NOT NULL,
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    CONSTRAINT fk_ads_teacher FOREIGN KEY (teacher_user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_ads_district FOREIGN KEY (district_id) REFERENCES districts(id),
    CONSTRAINT fk_ads_subject FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_user_id INT NOT NULL,
    reviewer_user_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT NOT NULL,
    is_hidden TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    CONSTRAINT fk_reviews_teacher FOREIGN KEY (teacher_user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_reviewer FOREIGN KEY (reviewer_user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT uq_review UNIQUE (teacher_user_id, reviewer_user_id)
);

CREATE INDEX idx_ads_district ON ads(district_id);
CREATE INDEX idx_ads_subject ON ads(subject_id);
CREATE INDEX idx_ads_language ON ads(language);
CREATE INDEX idx_ads_class_type ON ads(class_type);
CREATE INDEX idx_ads_status ON ads(status);
CREATE INDEX idx_ads_created ON ads(created_at);
CREATE INDEX idx_reviews_teacher ON reviews(teacher_user_id);
CREATE INDEX idx_reviews_hidden ON reviews(is_hidden);

INSERT INTO users (name, email, password_hash, role, status, created_at)
VALUES ('Admin User', 'admin@example.com', '$2y$12$eiM/ZoVYNJF69NC7CU/g/u7SGxV8c9dqpthuMzMA3TINxzZngJskm', 'admin', 'active', NOW());

INSERT INTO districts (name) VALUES
('Colombo'), ('Gampaha'), ('Kandy'), ('Galle'), ('Jaffna');

INSERT INTO subjects (name) VALUES
('Mathematics'), ('Science'), ('English'), ('ICT'), ('Commerce');
