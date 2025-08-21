CREATE DATABASE pdbureau;
USE pdbureau;

-- Table for admins (for authentication)
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL  -- Store hashed passwords
);

-- Default admin (password: admin123 – hash it in PHP)
INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$K.0zZfWj6yq8f6zZfWj6y.K.0zZfWj6yq8f6zZfWj6y');  -- Hashed 'admin123'

-- Table for departments
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL
);

-- Sample data
INSERT INTO departments (name, description) VALUES 
('Planning Dept', 'Handles urban planning and strategy.'),
('Development Dept', 'Oversees project execution and funding.');

-- Table for news/events (combined)
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    date DATE NOT NULL,
    image VARCHAR(255) DEFAULT NULL,  -- Path to optional image
    is_event TINYINT(1) DEFAULT 0  -- 1 for event, 0 for news
);

-- Sample data
INSERT INTO news (title, content, date, is_event) VALUES 
('Annual Planning Meeting', 'Details about the meeting.', '2025-08-20', 1);

-- Table for documents
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    type VARCHAR(50) NOT NULL,  -- e.g., pdf, docx
    path VARCHAR(255) NOT NULL,  -- Server path
    upload_date DATE NOT NULL
);

-- Table for contact messages
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    date DATE NOT NULL
);
