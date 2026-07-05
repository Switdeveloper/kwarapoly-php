-- Kwara State Polytechnic - School Fees System
-- Run this SQL to set up the database and tables

CREATE DATABASE IF NOT EXISTS kwarapoly_fees CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kwarapoly_fees;

CREATE TABLE IF NOT EXISTS students (
    matric_no VARCHAR(50) PRIMARY KEY,
    full_name VARCHAR(200) NOT NULL,
    department VARCHAR(200) NOT NULL,
    session VARCHAR(20) NOT NULL DEFAULT '2025/2026',
    parent_name VARCHAR(200) DEFAULT '',
    parent_phone VARCHAR(20) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_no VARCHAR(50) UNIQUE NOT NULL,
    student_matric VARCHAR(50) NOT NULL,
    student_name VARCHAR(200) NOT NULL,
    matric_no VARCHAR(50) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    term VARCHAR(20) NOT NULL,
    session VARCHAR(20) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    recorded_by VARCHAR(100) DEFAULT 'Admin',
    FOREIGN KEY (student_matric) REFERENCES students(matric_no) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a default admin user (password: admin123)
INSERT IGNORE INTO users (username, password_hash, role) VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
);
