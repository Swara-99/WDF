CREATE DATABASE IF NOT EXISTS studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studenthub;
CREATE TABLE IF NOT EXISTS users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 mobile VARCHAR(20) NOT NULL DEFAULT '',
 course VARCHAR(100) NOT NULL DEFAULT '',
 year_of_study TINYINT UNSIGNED NOT NULL DEFAULT 1,
 gender VARCHAR(30) NOT NULL DEFAULT 'Prefer not to say',
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('student','admin') NOT NULL DEFAULT 'student',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 last_login DATETIME NULL,
 INDEX idx_users_role(role), INDEX idx_users_name(name)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS students (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NULL UNIQUE,
 student_number VARCHAR(30) UNIQUE,
 department VARCHAR(100) NOT NULL DEFAULT '',
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_students_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS events (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(160) NOT NULL,
 category VARCHAR(80) NOT NULL DEFAULT 'General',
 event_date DATE NOT NULL,
 location VARCHAR(160) NOT NULL,
 description TEXT NOT NULL,
 poster VARCHAR(255) NULL,
 status ENUM('open','closed') NOT NULL DEFAULT 'open',
 created_by INT UNSIGNED NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_events_creator FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE SET NULL,
 INDEX idx_events_date(event_date), INDEX idx_events_category(category)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS registrations (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 event_id INT UNSIGNED NOT NULL,
 registered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_user_event(user_id,event_id),
 CONSTRAINT fk_reg_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 CONSTRAINT fk_reg_event FOREIGN KEY(event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS audit_logs (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NULL,
 action VARCHAR(80) NOT NULL,
 detail VARCHAR(250) NOT NULL DEFAULT '',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_audit_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
 INDEX idx_audit_created(created_at)
) ENGINE=InnoDB;
INSERT INTO events(title,category,event_date,location,description,status) VALUES
('Campus Tech Meetup','Technology','2026-11-05','Innovation Lab','Meet fellow builders and share ideas about emerging technology.','open'),
('Design Thinking Workshop','Workshop','2026-11-12','Seminar Hall A','Practice turning student problems into practical solutions.','open'),
('Cultural Evening','Cultural','2026-11-20','Open Air Theatre','Celebrate creativity, music and campus community.','open'),
('Career Readiness Session','Career','2026-11-25','Auditorium','Prepare your resume and learn interview fundamentals.','open'),
('Coding Sprint','Technology','2026-12-02','Computer Lab 2','Work in teams on a short coding challenge.','open');
