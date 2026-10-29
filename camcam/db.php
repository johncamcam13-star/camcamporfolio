<?php
/**
 * Database Connection & Data Model
 * IT Student Portfolio: John Carlo Camcam
 * Course: Bachelor of Science in Information Technology (BSIT)
 */

class Database {
    private static $host = 'localhost';
    private static $user = 'root';
    private static $pass = '';
    private static $dbname = 'portfolio_db';
    private static $pdo = null;
    private static $isConnected = false;
    private static $statusMessage = '';

    public static function getConnection() {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        try {
            $dsn = "mysql:host=" . self::$host . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 2
            ];
            
            $pdo = new PDO($dsn, self::$user, self::$pass, $options);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . self::$dbname . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo->exec("USE `" . self::$dbname . "`;");

            self::initializeTables($pdo);

            self::$pdo = $pdo;
            self::$isConnected = true;
            self::$statusMessage = "Connected to MySQL: " . self::$dbname;
            return self::$pdo;
        } catch (PDOException $e) {
            self::$isConnected = false;
            self::$statusMessage = "MySQL offline or unavailable. Running in high-fidelity fallback mode.";
            return null;
        }
    }

    public static function isConnected() {
        if (self::$pdo === null) {
            self::getConnection();
        }
        return self::$isConnected;
    }

    public static function getStatusMessage() {
        if (self::$pdo === null) {
            self::getConnection();
        }
        return self::$statusMessage;
    }

    private static function initializeTables($pdo) {
        // Table: profile_info
        $pdo->exec("CREATE TABLE IF NOT EXISTS `profile_info` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `full_name` VARCHAR(100) NOT NULL,
            `tagline` VARCHAR(255) NOT NULL,
            `degree` VARCHAR(150) NOT NULL DEFAULT 'Bachelor of Science in Information Technology',
            `bio` TEXT NOT NULL,
            `email` VARCHAR(120) NOT NULL,
            `phone` VARCHAR(50) DEFAULT '09516609480',
            `location` VARCHAR(100) DEFAULT 'Metro Manila, Philippines',
            `academic_standing` VARCHAR(100) DEFAULT 'Senior IT Student / Candidate for Graduation',
            `projects_completed` INT DEFAULT 3,
            `github_link` VARCHAR(255) DEFAULT 'https://github.com/johncarlocamcam',
            `linkedin_link` VARCHAR(255) DEFAULT 'https://linkedin.com/in/johncarlocamcam',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Self-Healing Migration: Add 'degree' column if table existed from an older version
        try {
            $checkDegree = $pdo->query("SHOW COLUMNS FROM `profile_info` LIKE 'degree'")->fetchAll();
            if (empty($checkDegree)) {
                $pdo->exec("ALTER TABLE `profile_info` ADD COLUMN `degree` VARCHAR(150) NOT NULL DEFAULT 'Bachelor of Science in Information Technology' AFTER `tagline`");
            }
        } catch (Exception $e) {
            // Migration completed or handled
        }

        // Self-Healing Migration: Add 'academic_standing' if missing
        try {
            $checkAcademic = $pdo->query("SHOW COLUMNS FROM `profile_info` LIKE 'academic_standing'")->fetchAll();
            if (empty($checkAcademic)) {
                $pdo->exec("ALTER TABLE `profile_info` ADD COLUMN `academic_standing` VARCHAR(100) DEFAULT 'Senior IT Student • Ready for OJT & Industry Deployment' AFTER `location`");
            }
        } catch (Exception $e) {
            // Migration completed or handled
        }

        // Seed or update Profile
        $stmt = $pdo->query("SELECT COUNT(*) FROM `profile_info`");
        if ($stmt->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO `profile_info` (`id`, `full_name`, `tagline`, `degree`, `bio`, `email`, `phone`, `location`, `academic_standing`, `projects_completed`, `github_link`, `linkedin_link`) VALUES
            (1, 'John Carlo Camcam', 'BSIT Student • Aspiring Full-Stack Web & IoT Developer', 'Bachelor of Science in Information Technology', 'Dedicated Information Technology student with hands-on experience in full-stack web development using PHP and MySQL, as well as embedded hardware programming with Arduino. Eager to leverage academic coursework, project experience, and technical problem-solving skills for internship (OJT) and junior developer opportunities.', 'johncamcam13@gmail.com', '09516609480', 'Metro Manila, Philippines', 'Senior IT Student • Ready for OJT & Industry Deployment', 3, 'https://github.com/johncarlocamcam', 'https://linkedin.com/in/johncarlocamcam');");
        } else {
            $pdo->exec("UPDATE `profile_info` SET 
                `full_name` = 'John Carlo Camcam',
                `tagline` = 'BSIT Student • Aspiring Full-Stack Web & IoT Developer',
                `degree` = 'Bachelor of Science in Information Technology',
                `academic_standing` = 'Senior IT Student • Ready for OJT & Industry Deployment',
                `email` = 'johncamcam13@gmail.com',
                `phone` = '09516609480'
                WHERE `id` = 1;");
        }

        // Table: projects
        $pdo->exec("CREATE TABLE IF NOT EXISTS `projects` (
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
            `display_order` INT D            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Self-Healing Migration: Add 'project_type' and 'badge' if missing
        try {
            $checkType = $pdo->query("SHOW COLUMNS FROM `projects` LIKE 'project_type'")->fetchAll();
            if (empty($checkType)) {
                $pdo->exec("ALTER TABLE `projects` ADD COLUMN `project_type` VARCHAR(80) DEFAULT 'Project' AFTER `category`");
            }
            $checkBadge = $pdo->query("SHOW COLUMNS FROM `projects` LIKE 'badge'")->fetchAll();
            if (empty($checkBadge)) {
                $pdo->exec("ALTER TABLE `projects` ADD COLUMN `badge` VARCHAR(50) DEFAULT 'Featured Project' AFTER `project_type`");
            }
        } catch (Exception $e) {}

        // Seed Projects if empty
        $stmt = $pdo->query("SELECT COUNT(*) FROM `projects`");
        if ($stmt->fetchColumn() == 0) {
            $projectsSql = "INSERT INTO `projects` (`title`, `slug`, `category`, `project_type`, `badge`, `short_desc`, `full_desc`, `features`, `technologies`, `image_url`, `demo_link`, `github_link`, `display_order`) VALUES
            (
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
            );";
            $pdo->exec($projectsSql);
        } else {
            // Ensure project types and demo links are updated
            $pdo->exec("UPDATE `projects` SET `project_type` = 'Web Development Project', `demo_link` = 'demo-tapseafood.php' WHERE `slug` = 'tapseafood-online-ordering';");
            $pdo->exec("UPDATE `projects` SET `project_type` = 'Systems Analysis Project', `demo_link` = 'demo-hotel.php' WHERE `slug` = 'hotel-online-booking';");
            $pdo->exec("UPDATE `projects` SET `project_type` = 'Hardware Project', `demo_link` = 'demo-arduino.php' WHERE `slug` = 'arduino-water-dispenser';");
        }

        // Table: skills
        $pdo->exec("CREATE TABLE IF NOT EXISTS `skills` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL UNIQUE,
            `category` VARCHAR(50) NOT NULL,
            `level` INT NOT NULL,
            `badge_type` ENUM('gold', 'silver') DEFAULT 'gold'
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        $stmt = $pdo->query("SELECT COUNT(*) FROM `skills`");
        if ($stmt->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO `skills` (`id`, `name`, `category`, `level`, `badge_type`) VALUES
            (1, 'PHP 8 & Object-Oriented Programming', 'Backend', 90, 'gold'),
            (2, 'MySQL Relational Database Design & Normalization', 'Database', 88, 'gold'),
            (3, 'HTML5 & Modern Responsive CSS3 (Gold/Silver)', 'Frontend', 92, 'gold'),
            (4, 'JavaScript (ES6+) & Dynamic DOM Manipulation', 'Frontend', 85, 'silver'),
            (5, 'Arduino Microcontrollers (C++) & Sensor Wiring', 'Hardware / IoT', 86, 'silver'),
            (6, 'Relational Schema Modeling & SQL Joins', 'Database', 89, 'gold'),
            (7, 'RESTful APIs & AJAX Asynchronous Requests', 'Backend', 84, 'silver'),
            (8, 'Software Development Life Cycle (SDLC) & Git', 'Methodology', 87, 'gold');");
        }

        // Table: contact_messages
        $pdo->exec("CREATE TABLE IF NOT EXISTS `contact_messages` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `sender_name` VARCHAR(100) NOT NULL,
            `sender_email` VARCHAR(120) NOT NULL,
            `subject` VARCHAR(150) NOT NULL,
            `message` TEXT NOT NULL,
            `ip_address` VARCHAR(45) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Table: admin_users (Authentication)
        $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `role` VARCHAR(50) DEFAULT 'admin',
            `last_login` DATETIME DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Seed admin user camcam / camcam12345
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM `admin_users` WHERE `username` = ?");
            $stmt->execute(['camcam']);
            if ($stmt->fetchColumn() == 0) {
                $hash = password_hash('camcam12345', PASSWORD_DEFAULT);
                $ins = $pdo->prepare("INSERT INTO `admin_users` (`username`, `password`, `role`) VALUES (?, ?, 'admin')");
                $ins->execute(['camcam', $hash]);
            }
        } catch (Exception $e) {}
    }

    /**
     * Fetch Profile Data for John Carlo Camcam (IT Student)
     */
    public static function getProfile() {
        $pdo = self::getConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT * FROM `profile_info` ORDER BY id ASC LIMIT 1");
                $data = $stmt->fetch();
                if ($data) return $data;
            } catch (Exception $e) {}
        }

        // High-Fidelity Fallback Dataset
        return [
            'full_name' => 'John Carlo Camcam',
            'tagline' => 'BSIT Student • Aspiring Full-Stack Web & IoT Developer',
            'degree' => 'Bachelor of Science in Information Technology',
            'bio' => 'Dedicated Information Technology student with hands-on experience in full-stack web development using PHP and MySQL, as well as embedded hardware programming with Arduino. Eager to leverage academic coursework, project experience, and technical problem-solving skills for internship (OJT) and junior developer opportunities.',
            'email' => 'johncamcam13@gmail.com',
            'phone' => '09516609480',
            'location' => 'Metro Manila, Philippines',
            'academic_standing' => 'Senior IT Student • Ready for OJT & Industry Deployment',
            'projects_completed' => 3,
            'github_link' => 'https://github.com/johncarlocamcam',
            'linkedin_link' => 'https://linkedin.com/in/johncarlocamcam'
        ];
    }

    /**
     * Fetch Projects
     */
    public static function getProjects() {
        $pdo = self::getConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT * FROM `projects` ORDER BY display_order ASC");
                $data = $stmt->fetchAll();
                if ($data && count($data) > 0) return $data;
            } catch (Exception $e) {}
        }

        return [
            [
                'id' => 1,
                'title' => 'Tapseafood: Online Food Ordering',
                'slug' => 'tapseafood-online-ordering',
                'category' => 'Web Systems & E-Commerce',
                'project_type' => 'Web Development Project',
                'badge' => 'Gold Project',
                'short_desc' => 'A full-stack seafood ordering web application featuring a dynamic menu catalog, real-time cart calculations, checkout processing, and administrative order tracking.',
                'full_desc' => 'Developed as a comprehensive web systems project by John Carlo Camcam, Tapseafood simulates an authentic online restaurant ordering portal. It empowers customers to browse fresh seafood products with weight-based pricing, add items to an active session cart, compute delivery fees automatically, and submit orders directly to a MySQL database with real-time status updates.',
                'features' => 'Dynamic seafood menu with weight and category filtering;Live session-based shopping cart with subtotal, tax, and delivery fee computation;Order status tracking workflow (Pending, Preparing, Out for Delivery, Completed);Administrative dashboard for managing menu items, pricing, and viewing order records;Responsive user interface optimized for desktop and mobile devices.',
                'technologies' => 'PHP 8, MySQL RDBMS, JavaScript, HTML5, CSS3, AJAX, Session Auth',
                'image_url' => 'assets/images/tapseafood.svg',
                'demo_link' => 'demo-tapseafood.php',
                'github_link' => 'https://github.com/johncarlocamcam/tapseafood-ordering',
                'display_order' => 1
            ],
            [
                'id' => 2,
                'title' => 'Hotel Online Booking',
                'slug' => 'hotel-online-booking',
                'category' => 'Database Systems & Web Apps',
                'project_type' => 'Systems Analysis Project',
                'badge' => 'Platinum Project',
                'short_desc' => 'An online hotel reservation platform with dynamic date-range availability validation, room suite showrooms, and automated booking confirmation receipt generation.',
                'full_desc' => 'Engineered by John Carlo Camcam as a course project for Database Management and Web Programming. The Hotel Online Booking system replaces manual pen-and-paper reservation ledgers with an automated web platform. It enforces database constraints to prevent room double-booking, calculates multi-night room rates, and generates printable confirmation codes for guests.',
                'features' => 'Interactive room suite catalog with amenity filters and room capacity information;Check-in and check-out date picker with automated conflict validation;Automated booking confirmation code generator and printable invoice;Guest preference configuration (bed choices, guest counts, special requests);Admin management module for viewing room occupancy schedules and booking history.',
                'technologies' => 'PHP 8, MySQL Relational Schema, JavaScript, Flatpickr DatePicker, CSS3 Grid, Bootstrap Components',
                'image_url' => 'assets/images/hotel_booking.svg',
                'demo_link' => 'demo-hotel.php',
                'github_link' => 'https://github.com/johncarlocamcam/hotel-online-booking',
                'display_order' => 2
            ],
            [
                'id' => 3,
                'title' => 'Arduino Water Dispenser',
                'slug' => 'arduino-water-dispenser',
                'category' => 'Embedded Systems & IoT',
                'project_type' => 'Hardware Project',
                'badge' => 'Silver Project',
                'short_desc' => 'An automated contactless smart water dispensing device powered by an Arduino UNO microcontroller, ultrasonic proximity sensor, and automated relay valve control.',
                'full_desc' => 'Conceived and wired by John Carlo Camcam for an Embedded Systems and IoT hardware project. This system eliminates physical touch and reduces germ transmission in public hydration stations. An HC-SR04 ultrasonic distance sensor continuously monitors cup proximity. When a container is detected within 5 cm, the microcontroller triggers a 5V relay module connected to a 12V food-grade solenoid valve. An I2C 16x2 LCD provides real-time user feedback and volume statistics logged to a MySQL database.',
                'features' => 'Contactless ultrasonic distance detection to ensure sterile, touch-free operation;Calibrated volumetric cutoff algorithm to prevent cup overflows and liquid waste;I2C 16x2 alphanumeric LCD screen displaying pouring state and container proximity;Hardware safety timeout automatically shutting off the relay upon obstruction;Serial-to-PHP bridge logging hydration statistics and timestamps into MySQL.',
                'technologies' => 'Arduino UNO (C++), HC-SR04 Ultrasonic Sensor, 5V Relay Module, 12V Solenoid Valve, I2C 16x2 LCD, PHP/MySQL Telemetry',
                'image_url' => 'assets/images/arduino_dispenser.svg',
                'demo_link' => 'demo-arduino.php',
                'github_link' => 'https://github.com/johncarlocamcam/arduino-water-dispenser',
                'display_order' => 3
            ]
        ];
    }

    /**
     * Fetch Skills
     */
    public static function getSkills() {
        $pdo = self::getConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT * FROM `skills` ORDER BY id ASC");
                $data = $stmt->fetchAll();
                if ($data && count($data) > 0) return $data;
            } catch (Exception $e) {}
        }

        return [
            ['name' => 'PHP 8 & Object-Oriented Programming', 'category' => 'Backend', 'level' => 90, 'badge_type' => 'gold'],
            ['name' => 'MySQL Relational Database Design & Normalization', 'category' => 'Database', 'level' => 88, 'badge_type' => 'gold'],
            ['name' => 'HTML5 & Modern Responsive CSS3 (Gold/Silver)', 'category' => 'Frontend', 'level' => 92, 'badge_type' => 'gold'],
            ['name' => 'JavaScript (ES6+) & Dynamic DOM Manipulation', 'category' => 'Frontend', 'level' => 85, 'badge_type' => 'silver'],
            ['name' => 'Arduino Microcontrollers (C++) & Sensor Wiring', 'category' => 'Hardware / IoT', 'level' => 86, 'badge_type' => 'silver'],
            ['name' => 'Relational Schema Modeling & SQL Joins', 'category' => 'Database', 'level' => 89, 'badge_type' => 'gold'],
            ['name' => 'RESTful APIs & AJAX Asynchronous Requests', 'category' => 'Backend', 'level' => 84, 'badge_type' => 'silver'],
            ['name' => 'Software Development Life Cycle (SDLC) & Git', 'category' => 'Methodology', 'level' => 87, 'badge_type' => 'gold']
        ];
    }

    /**
     * Save Contact Message
     */
    public static function saveMessage($name, $email, $subject, $message) {
        $pdo = self::getConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO `contact_messages` (`sender_name`, `sender_email`, `subject`, `message`, `ip_address`) VALUES (?, ?, ?, ?, ?)");
                $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                $stmt->execute([$name, $email, $subject, $message, $ip]);
                return ['success' => true, 'source' => 'mysql'];
            } catch (Exception $e) {
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }
        return ['success' => true, 'source' => 'demo'];
    }

    /**
     * Authenticate User: username=camcam, password=camcam12345
     */
    public static function verifyLogin($username, $password) {
        $username = trim($username);
        $password = trim($password);

        // Always valid for requested credentials
        if ($username === 'camcam' && $password === 'camcam12345') {
            $pdo = self::getConnection();
            if ($pdo) {
                try {
                    $stmt = $pdo->prepare("UPDATE `admin_users` SET `last_login` = NOW() WHERE `username` = ?");
                    $stmt->execute([$username]);
                } catch (Exception $e) {}
            }
            return ['success' => true, 'username' => 'camcam', 'role' => 'admin'];
        }

        // Database Verification
        $pdo = self::getConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM `admin_users` WHERE `username` = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();
                if ($user) {
                    if (password_verify($password, $user['password']) || $password === $user['password']) {
                        $upd = $pdo->prepare("UPDATE `admin_users` SET `last_login` = NOW() WHERE `id` = ?");
                        $upd->execute([$user['id']]);
                        return ['success' => true, 'username' => $user['username'], 'role' => $user['role']];
                    }
                }
            } catch (Exception $e) {}
        }

        return ['success' => false, 'error' => 'Invalid username or password. Please try again.'];
    }

    /**
     * Fetch Contact Messages for Admin Dashboard
     */
    public static function getMessages() {
        $pdo = self::getConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT * FROM `contact_messages` ORDER BY `id` DESC");
                $data = $stmt->fetchAll();
                if ($data) return $data;
            } catch (Exception $e) {}
        }
        return [];
    }
}
