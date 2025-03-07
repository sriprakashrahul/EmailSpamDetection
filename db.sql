-- Create the database
CREATE DATABASE IF NOT EXISTS spam_detection;
USE spam_detection;

-- Create the `users` table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    us_fname VARCHAR(50) NOT NULL,
    us_lname VARCHAR(50) NOT NULL,
    us_usname VARCHAR(50) NOT NULL UNIQUE,
    us_pass VARCHAR(255) NOT NULL
);

-- Create the `mail` table
CREATE TABLE IF NOT EXISTS mail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    msg_from VARCHAR(50) NOT NULL,
    msg_to VARCHAR(50) NOT NULL,
    msg_sub VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    msg_date DATETIME NOT NULL
);

-- Create the `spam` table
CREATE TABLE IF NOT EXISTS spam (
    id INT AUTO_INCREMENT PRIMARY KEY,
    msg_from VARCHAR(50) NOT NULL,
    msg_to VARCHAR(50) NOT NULL,
    msg_sub VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    msg_date DATETIME NOT NULL
);

-- Insert sample data into the `users` table
INSERT INTO users (us_fname, us_lname, us_usname, us_pass) 
VALUES 
('John', 'Doe', 'john_doe', '$2y$10$examplehashedpassword'), -- Replace with actual hashed password
('Jane', 'Smith', 'jane_smith', '$2y$10$examplehashedpassword'); -- Replace with actual hashed password

-- Insert sample data into the `mail` table
INSERT INTO mail (msg_from, msg_to, msg_sub, message, msg_date) 
VALUES 
('john_doe', 'jane_smith', 'Hello', 'This is a test email.', NOW()),
('jane_smith', 'john_doe', 'Re: Hello', 'This is a reply to your email.', NOW());

-- Insert sample data into the `spam` table
INSERT INTO spam (msg_from, msg_to, msg_sub, message, msg_date) 
VALUES 
('spammer', 'john_doe', 'Win a Prize!', 'Click here to claim your prize!', NOW());