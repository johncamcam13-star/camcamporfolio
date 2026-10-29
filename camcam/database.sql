-- ====================================================================
-- IT Student Portfolio Database Schema & Seed Data
-- Student Developer: John Carlo Camcam
-- Course: Bachelor of Science in Information Technology (BSIT)
-- Flagship Projects: Tapseafood, Hotel Online Booking, Arduino Water Dispenser
-- Compatible with: MySQL 5.7+, MySQL 8.0+, MariaDB 10+, phpMyAdmin
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------------------
-- Database: portfolio_db
-- --------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `portfolio_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `portfolio_db`;

-- --------------------------------------------------------------------
-- 1. Table: profile_info
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `profile_info`;
CREATE TABLE `profile_info` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `tagline` VARCHAR(255) NOT NULL,
    `degree` VARCHAR(150) NOT NULL DEFAULT 'BS in Information Technology',
    `bio` TEXT NOT NULL,
    `email` VARCHAR(120) NOT NULL,
    `phone` VARCHAR(50) DEFAULT '09516609480',
    `location` VARCHAR(100) DEFAULT 'Metro Manila, Philippines',
    `academic_standing` VARCHAR(100) DEFAULT 'Senior IT Student / Candidate for Graduation',
    `projects_completed` INT DEFAULT 3,
    `github_link` VARCHAR(255) DEFAULT 'https://github.com/johncarlocamcam',
    `linkedin_link` VARCHAR(255) DEFAULT 'https://linkedin.com/in/johncarlocamcam',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Profile for John Carlo Camcam (IT Student)
INSERT INTO `profile_info` (`id`, `full_name`, `tagline`, `degree`, `bio`, `email`, `phone`, `location`, `academic_standing`, `projects_completed`, `github_link`, `linkedin_link`)
VALUES (
    1,
    'John Carlo Camcam',
    'BSIT Student • Aspiring Full-Stack Web & IoT Developer',
    'Bachelor of Science in Information Technology',
    'Dedicated and detail-oriented Information Technology student with hands-on experience in full-stack web development using PHP and MySQL, as well as embedded hardware programming with Arduino. Eager to leverage academic coursework, project experience, and technical problem-solving skills for internship (OJT) and junior developer opportunities.',
    'johncamcam13@gmail.com',
    '09516609480',
    'Metro Manila, Philippines',
    'Senior IT Student • Ready for OJT & Industry Deployment',
    3,
    'https://github.com/johncarlocamcam',
    'https://linkedin.com/in/johncarlocamcam'
);

-- --------------------------------------------------------------------
-- 2. Table: projects
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `category` VARCHAR(80) NOT NULL,
    `project_type` VARCHAR(80) DEFAULT 'Project',
    `badge` VARCHAR(50) DEFAULT 'Featured Project',
    `short_desc` VARCHAR(255) NOT NULL,
    `full_desc` TEXT NOT NULL,
    `features` TEXT NOT NULL,
    `technologies` VARCHAR(255) NOT NULL,
    `image_url` VARCHAR(255) NOT NULL,
    `demo_link` VARCHAR(255) DEFAULT '#',
    `github_link` VARCHAR(255) DEFAULT '#',
    `display_order` INT DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed John Carlo Camcam's 3 Flagship Projects
INSERT INTO `projects` (`id`, `title`, `slug`, `category`, `project_type`, `badge`, `short_desc`, `full_desc`, `features`, `technologies`, `image_url`, `demo_link`, `github_link`, `display_order`)
VALUES
(
    1,
    'Tapseafood: Online Food Ordering',
    'tapseafood-online-ordering',
    'Web Systems & E-Commerce',
    'Web Development Project',
    'Gold Project',
    'A full-stack seafood ordering web application featuring a dynamic menu catalog, real-time cart calculations, checkout processing, and administrative order tracking.',
    'Developed as a comprehensive web systems project by John Carlo Camcam, Tapseafood simulates an authentic online restaurant ordering portal. It empowers customers to browse fresh seafood products with weight-based pricing, add items to an active session cart, compute delivery fees automatically, and submit orders directly to a MySQL database with real-time status updates.',
    'Dynamic seafood menu with weight and category filtering;Live session-based shopping cart with subtotal, tax, and delivery fee computation;Order status tracking workflow (Pending, Preparing, Out for Delivery, Completed);Administrative dashboard for managing menu items, pricing, and viewing order records;Responsive user interface optimized for desktop and mobile devices.',
    'PHP 8, MySQL RDBMS, JavaScript, HTML5, CSS3, AJAX, Session Auth',
    'assets/images/tapseafood.svg',
    'demo-tapseafood.php',
    'https://github.com/johncarlocamcam/tapseafood-ordering',
    1
),
(
    2,
    'Hotel Online Booking',
    'hotel-online-booking',
    'Database Systems & Web Apps',
    'Systems Analysis Project',
    'Platinum Project',
    'An online hotel reservation platform with dynamic date-range availability validation, room suite showrooms, and automated booking confirmation receipt generation.',
    'Engineered by John Carlo Camcam as a course project for Database Management and Web Programming. The Hotel Online Booking system replaces manual pen-and-paper reservation ledgers with an automated web platform. It enforces database constraints to prevent room double-booking, calculates multi-night room rates, and generates printable confirmation codes for guests.',
    'Interactive room suite catalog with amenity filters and room capacity information;Check-in and check-out date picker with automated conflict validation;Automated booking confirmation code generator and printable invoice;Guest preference configuration (bed choices, guest counts, special requests);Admin management module for viewing room occupancy schedules and booking history.',
    'PHP 8, MySQL Relational Schema, JavaScript, Flatpickr DatePicker, CSS3 Grid, Bootstrap Components',
    'assets/images/hotel_booking.svg',
    'demo-hotel.php',
    'https://github.com/johncarlocamcam/hotel-online-booking',
    2
),
(
    3,
    'Arduino Water Dispenser',
    'arduino-water-dispenser',
    'Embedded Systems & IoT',
    'Hardware Project',
    'Silver Project',
    'An automated contactless smart water dispensing device powered by an Arduino UNO microcontroller, ultrasonic proximity sensor, and automated relay valve control.',
    'Conceived and wired by John Carlo Camcam for an Embedded Systems and IoT hardware project. This system eliminates physical touch and reduces germ transmission in public hydration stations. An HC-SR04 ultrasonic distance sensor continuously monitors cup proximity. When a container is detected within 5 cm, the microcontroller triggers a 5V relay module connected to a 12V food-grade solenoid valve. An I2C 16x2 LCD provides real-time user feedback and volume statistics logged to a MySQL database.',
    'Contactless ultrasonic distance detection to ensure sterile, touch-free operation;Calibrated volumetric cutoff algorithm to prevent cup overflows and liquid waste;I2C 16x2 alphanumeric LCD screen displaying pouring state and container proximity;Hardware safety timeout automatically shutting off the relay upon obstruction;Serial-to-PHP bridge logging hydration statistics and timestamps into MySQL.',
    'Arduino UNO (C++), HC-SR04 Ultrasonic Sensor, 5V Relay Module, 12V Solenoid Valve, I2C 16x2 LCD, PHP/MySQL Telemetry',
    'assets/images/arduino_dispenser.svg',
    'demo-arduino.php',
    'https://github.com/johncarlocamcam/arduino-water-dispenser',
    3
);

-- --------------------------------------------------------------------
-- 3. Table: skills
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `category` VARCHAR(50) NOT NULL,
    `level` INT NOT NULL,
    `badge_type` ENUM('gold', 'silver') DEFAULT 'gold'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Academic & Technical Skills for John Carlo Camcam
INSERT INTO `skills` (`id`, `name`, `category`, `level`, `badge_type`) VALUES
(1, 'PHP 8 & Object-Oriented Programming', 'Backend', 90, 'gold'),
(2, 'MySQL Relational Database Design & Normalization', 'Database', 88, 'gold'),
(3, 'HTML5 & Modern Responsive CSS3 (Gold/Silver)', 'Frontend', 92, 'gold'),
(4, 'JavaScript (ES6+) & Dynamic DOM Manipulation', 'Frontend', 85, 'silver'),
(5, 'Arduino Microcontrollers (C++) & Sensor Wiring', 'Hardware / IoT', 86, 'silver'),
(6, 'Relational Schema Modeling & SQL Joins', 'Database', 89, 'gold'),
(7, 'RESTful APIs & AJAX Asynchronous Requests', 'Backend', 84, 'silver'),
(8, 'Software Development Life Cycle (SDLC) & Git', 'Methodology', 87, 'gold');

-- --------------------------------------------------------------------
-- 4. Table: contact_messages
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sender_name` VARCHAR(100) NOT NULL,
    `sender_email` VARCHAR(120) NOT NULL,
    `subject` VARCHAR(150) NOT NULL,
    `message` TEXT NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed sample message from recruiters
INSERT INTO `contact_messages` (`id`, `sender_name`, `sender_email`, `subject`, `message`, `ip_address`)
VALUES 
(1, 'Tech Innovations Recruitment', 'careers@techinnovations.ph', 'OJT Practicum (500 Hours) - Junior Web Developer', 'Good day, John Carlo Camcam! We reviewed your IT student portfolio and flagship projects (Tapseafood & Hotel Booking). We would like to invite you for an internship interview for our 500-hour Practicum program.', '127.0.0.1');

-- --------------------------------------------------------------------
-- 5. Table: admin_users (Authentication)
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) DEFAULT 'admin',
    `last_login` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Admin User (Username: camcam | Password: camcam12345)
INSERT INTO `admin_users` (`id`, `username`, `password`, `role`)
VALUES (1, 'camcam', 'camcam12345', 'admin');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
