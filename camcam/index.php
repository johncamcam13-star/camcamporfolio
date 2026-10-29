<?php
/**
 * Student IT Portfolio - Bachelor of Science in Information Technology (BSIT)
 * Student Developer: John Carlo Camcam
 * Flagship Projects:
 *   1. Tapseafood: Online Food Ordering (Web Systems & E-Commerce)
 *   2. Hotel Online Booking (Database Management & Reservation System)
 *   3. Arduino Water Dispenser (Embedded Systems & IoT Hardware)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['logged_user']);
    unset($_SESSION['user_role']);
    session_destroy();
    header('Location: index.php');
    exit;
}

// Handle Login Submission (username: camcam / password: camcam12345)
$loginStatus = null;
$loginMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_login'])) {
    $loginUser = trim($_POST['username'] ?? '');
    $loginPass = trim($_POST['password'] ?? '');

    $authResult = Database::verifyLogin($loginUser, $loginPass);
    if ($authResult['success']) {
        $_SESSION['logged_user'] = $authResult['username'];
        $_SESSION['user_role'] = $authResult['role'];
        $loginStatus = 'success';
        $loginMessage = 'Welcome back, ' . htmlspecialchars($authResult['username']) . '! You are now authenticated.';
    } else {
        $loginStatus = 'error';
        $loginMessage = $authResult['error'] ?? 'Invalid username or password.';
    }
}

$isLoggedIn = !empty($_SESSION['logged_user']);
$currentUser = $_SESSION['logged_user'] ?? '';
$adminMessages = $isLoggedIn ? Database::getMessages() : [];

// Handle Contact / OJT Inquiry Form Submission
$formStatus = null;
$formMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name = trim(filter_input(INPUT_POST, 'sender_name', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'sender_email', FILTER_SANITIZE_EMAIL));
    $subject = trim(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS));
    $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $formStatus = 'error';
        $formMessage = 'All fields are required. Please complete the form before submitting.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formStatus = 'error';
        $formMessage = 'Please provide a valid email address.';
    } else {
        $saveResult = Database::saveMessage($name, $email, $subject, $message);
        if ($saveResult['success']) {
            $formStatus = 'success';
            $formMessage = 'Thank you, ' . htmlspecialchars($name) . '! Your message has been recorded into MySQL. John Carlo Camcam will respond to you promptly.';
        } else {
            $formStatus = 'error';
            $formMessage = 'Could not record message: ' . htmlspecialchars($saveResult['error']);
        }
    }
}

// Fetch Dynamic Profile, Projects & Skills
$profile = Database::getProfile();
$projects = Database::getProjects();
$skills = Database::getSkills();
$isDbConnected = Database::isConnected();
$dbStatusMsg = Database::getStatusMessage();

// User Portrait Integration for John Carlo Camcam
$uploadedPhotoPath = 'C:/Users/Administrator/.gemini/antigravity-ide/brain/8bfd3819-0562-4428-82f7-f805c6d6bd3f/.user_uploaded/media_1792762619244.png';
$localPhotoPath = __DIR__ . '/assets/images/john_carlo_camcam.png';

if (!file_exists($localPhotoPath) && file_exists($uploadedPhotoPath)) {
    @copy($uploadedPhotoPath, $localPhotoPath);
}

if (file_exists($localPhotoPath)) {
    $profileImageSrc = 'assets/images/john_carlo_camcam.png';
} elseif (file_exists($uploadedPhotoPath)) {
    $profileImageSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($uploadedPhotoPath));
} else {
    $profileImageSrc = 'assets/images/profile_avatar.svg';
}

// User Resume Image Integration (Supports student_resume.png & student_resume.pgn)
$uploadedResumePath = 'C:/Users/Administrator/.gemini/antigravity-ide/brain/3596b4bd-bef4-401a-bd28-2ecbaad8af49/.user_uploaded/media_1793259344760.png';
$localResumePath = __DIR__ . '/assets/images/student_resume.png';
$localResumePgn = __DIR__ . '/assets/images/student_resume.pgn';

if (!file_exists($localResumePath) && file_exists($uploadedResumePath)) {
    @copy($uploadedResumePath, $localResumePath);
}

// Synchronize student_resume.png and student_resume.pgn
if (file_exists($localResumePath) && !file_exists($localResumePgn)) {
    @copy($localResumePath, $localResumePgn);
} elseif (file_exists($localResumePgn) && !file_exists($localResumePath)) {
    @copy($localResumePgn, $localResumePath);
}

if (file_exists($localResumePath)) {
    $resumeImageSrc = 'assets/images/student_resume.png';
} elseif (file_exists($localResumePgn)) {
    $resumeImageSrc = 'assets/images/student_resume.pgn';
} elseif (file_exists($uploadedResumePath)) {
    $resumeImageSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($uploadedResumePath));
} else {
    $resumeImageSrc = 'assets/images/student_resume.png';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($profile['full_name']) ?> | IT Student Portfolio (BSIT)</title>
  <meta name="description" content="Student IT portfolio of John Carlo Camcam (BS Information Technology). Showcasing Projects: Tapseafood, Hotel Online Booking, and Arduino Water Dispenser with MySQL & PHP.">
  <link rel="stylesheet" href="assets/css/style.css?v=<?= file_exists(__DIR__ . '/assets/css/style.css') ? filemtime(__DIR__ . '/assets/css/style.css') : time() ?>">
  <link rel="icon" type="image/svg+xml" href="assets/images/profile_avatar.svg">
</head>
<body>

  <!-- Sticky Modern Luxury Navigation Bar -->
  <header class="navbar" id="navbar">
    <div class="container nav-container">
      <a href="#hero" class="brand-link" id="brandLogo">
        <div class="brand-crest">
          <span class="crest-initials">JCC</span>
        </div>
        <div class="brand-meta">
          <span class="brand-name"><span class="gold-gradient-text"><?= htmlspecialchars($profile['full_name']) ?></span></span>
          <span class="brand-subtitle">BS Information Technology • Projects Portfolio</span>
        </div>
      </a>

      <nav>
        <ul class="nav-links" id="navLinks">
          <li><a href="#hero" class="nav-link active">Home</a></li>
          <li><a href="#academic" class="nav-link">Academic Journey</a></li>
          <li><a href="#projects" class="nav-link">Projects</a></li>
          <li><a href="#interview" class="nav-link">Mock Interview</a></li>
          <li><a href="#contact" class="nav-link">OJT / Contact</a></li>
        </ul>
      </nav>

      <div class="nav-actions">
        <!-- Student Resume Modal Popup Trigger Button -->
        <button class="btn btn-silver btn-sm" id="navResumeBtn" data-action="open-resume" onclick="openResumeModal(event)" title="Click to view Student Resume as a popup modal">
          <span>📄 Student Resume</span>
        </button>
        <?php if ($isLoggedIn): ?>
          <button class="btn btn-gold btn-sm" id="navAdminBtn" title="View Inquiries & Messages">
            <span>👑 <?= htmlspecialchars($currentUser) ?> (<?= count($adminMessages) ?>)</span>
          </button>
          <a href="index.php?logout=1" class="btn btn-silver btn-sm" title="Log Out of Admin Portal">
            <span>🚪 Logout</span>
          </a>
        <?php else: ?>
          <button class="btn btn-gold btn-sm" id="navLoginBtn" title="Admin Login">
            <span>🔐 Login</span>
          </button>
        <?php endif; ?>

        <a href="#contact" class="btn btn-outline-gold btn-sm" id="navContactBtn">
          <span>Inquire / OJT</span>
        </a>

        <button class="nav-toggle-btn" id="navToggle" aria-label="Toggle navigation menu">
          ☰
        </button>
      </div>
    </div>
  </header>

  <main>
    <!-- HERO SECTION (STUDENT IT SPOTLIGHT) -->
    <section class="hero" id="hero">
      <div class="container hero-grid">
        <!-- Left Hero Content -->
        <div class="hero-left">
          <div class="hero-eyebrow">
            <span class="eyebrow-icon">🎓</span>
            <span>Bachelor of Science in Information Technology</span>
            <span class="bullet">•</span>
            <span>IT Student Portfolio</span>
          </div>

          <h1 class="hero-title">
            Aspiring IT<span class="gold-gradient-text">Professional |</span> &amp; Web Development<span class="silver-gradient-text">IoT Hardware</span>
          </h1>

          <p class="hero-description">
            Hello! I am <strong class="highlight-name"><?= htmlspecialchars($profile['full_name']) ?></strong>, an Information Technology student specializing in backend architecture with <strong>PHP &amp; MySQL</strong> and physical microcontroller programming with <strong>Arduino</strong>. Ready for <strong>OJT / IT Internship (500 Hours)</strong> to build reliable, database-driven enterprise systems.
          </p>

          <div class="hero-cta">
            <a href="#projects" class="btn btn-gold" id="exploreProjectsBtn">
              <span>✦ View 3 Projects</span>
            </a>
            <button class="btn btn-silver" id="heroCvBtn" data-action="open-resume" onclick="openResumeModal(event)" title="Click to view Student Resume as a popup modal">
              <span>📄 Student Resume</span>
            </button>
            <a href="#contact" class="btn btn-outline-gold">
              <span>Inquire / OJT</span>
            </a>
          </div>

          <!-- Academic & Student Metrics -->
          <div class="hero-metrics">
            <div class="metric-item">
              <div class="metric-number gold-gradient-text">03</div>
              <div class="metric-label">Flagship Projects</div>
              <div class="metric-sub">Tapseafood • Hotel • Arduino</div>
            </div>
            <div class="metric-item">
              <div class="metric-number silver-gradient-text">BSIT</div>
              <div class="metric-label">Academic Program</div>
              <div class="metric-sub">Major in Web &amp; Systems</div>
            </div>
            <div class="metric-item">
              <div class="metric-number silver-gradient-text">500h</div>
              <div class="metric-label">OJT Ready</div>
              <div class="metric-sub">Practicum Available</div>
            </div>
          </div>
        </div>

        <!-- Right Hero: Student Developer Credential Card -->
        <div class="hero-right">
          <div class="student-credential-card">
            <!-- Smart Card Header -->
            <div class="card-chip-header">
              <div class="card-chip">
                <div class="chip-line"></div>
                <div class="chip-line"></div>
                <div class="chip-core"></div>
              </div>
              <div class="card-id-meta">
                <span class="card-id-tag">STUDENT DEVELOPER ID</span>
                <span class="card-id-num">BSIT-2026-JCC</span>
              </div>
              <div class="card-status-dot-wrap" title="Active Student Developer">
                <span class="pulse-dot"></span>
              </div>
            </div>

            <!-- Portrait in Dual Metallic Gold & Silver Frame -->
            <div class="profile-photo-container">
              <img src="<?= htmlspecialchars($profileImageSrc) ?>" alt="<?= htmlspecialchars($profile['full_name']) ?>" class="profile-photo-img">
              <div class="photo-status-badge">
                <span class="status-pulse-green"></span>
                <span>Ready for OJT / Internship</span>
              </div>
            </div>

            <h2 class="profile-card-name gold-gradient-text"><?= htmlspecialchars($profile['full_name']) ?></h2>
            <div class="profile-card-degree">Bachelor of Science in Information Technology</div>
            <div class="profile-card-academic">Candidate for Graduation • Project Lead</div>

            <!-- Core Academic Competency Chips -->
            <div class="profile-chips-grid">
              <div class="chip-item gold">
                <span class="chip-icon">⚡</span> Full-Stack PHP 8
              </div>
              <div class="chip-item silver">
                <span class="chip-icon">🗄️</span> MySQL RDBMS
              </div>
              <div class="chip-item gold">
                <span class="chip-icon">🤖</span> Arduino IoT C++
              </div>
              <div class="chip-item silver">
                <span class="chip-icon">📐</span> Systems Analysis
              </div>
            </div>

            <!-- Quick Card Action Bar -->
            <div class="card-action-bar">
              <button class="card-btn-action" id="cardResumeTrigger" data-action="open-resume" onclick="openResumeModal(event)" title="Click to view Student Resume as a popup modal">
                <span>📄 Student Resume</span>
              </button>
              <button class="card-btn-action" id="cardEmailCopyBtn" data-email="<?= htmlspecialchars($profile['email']) ?>">
                <span>✉️ Copy Email</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ACADEMIC JOURNEY & TECHNICAL FOUNDATION SECTION -->
    <section class="about-section" id="academic">
      <div class="container">
        <div class="section-header">
          <div class="student-badge-pill">Academic Foundation • BSIT Curriculum</div>
          <h2 class="section-title">My Journey in <span class="gold-gradient-text">Information Technology</span></h2>
          <div class="gold-divider-bar"></div>
          <p class="section-subtitle" style="margin-top: 1.25rem;">
            Bridging foundational computing theories with practical full-stack software and physical microcontroller engineering.
          </p>
        </div>

        <div class="about-grid">
          <!-- Left: Student Narrative & Objective -->
          <div class="about-narrative-box">
            <h3 class="narrative-heading gold-gradient-text">Academic Commitment &amp; Learning Philosophy</h3>
            <p class="about-text-lead">
              As an Information Technology student, I emphasize building complete, functioning software and hardware systems rather than stopping at conceptual design. Throughout my academic curriculum, I have mastered designing relational entity structures (ERDs), writing secure PHP backend logic with PDO prepared statements, and interfacing microcontrollers with physical sensors.
            </p>
            <p class="about-text-body">
              My major coursework in <strong>Database Management Systems</strong>, <strong>Web Systems &amp; Technologies</strong>, and <strong>Embedded IoT Systems</strong> directly fueled the development of my three flagship projects: <em>Tapseafood Online Food Ordering</em>, <em>Hotel Online Booking</em>, and the <em>Smart Arduino Water Dispenser</em>.
            </p>

            <div class="ojt-readiness-highlight">
              <div class="ojt-badge-row">
                <span class="ojt-status-icon">🎯</span>
                <div>
                  <h4 class="ojt-badge-title">Internship (OJT) Objective</h4>
                  <p class="ojt-badge-desc">Seeking a <strong>486 to 500-hour</strong> industry immersion as a Junior Web Developer, PHP/MySQL Backend Programmer, or Systems Associate to solve authentic business challenges.</p>
                </div>
              </div>
            </div>

            <div class="about-action-links">
              <a href="#projects" class="btn btn-gold btn-sm">Inspect 3 Projects</a>
              <button class="btn btn-silver btn-sm" id="aboutResumeBtn" data-action="open-resume" onclick="openResumeModal(event)" title="Click to view Student Resume as a popup modal">
                <span>📄 Student Resume</span>
              </button>
            </div>
          </div>

          <!-- Right: 4 Curriculum Milestones Cards -->
          <div class="about-highlights-list">
            <div class="about-highlight-item">
              <div class="about-highlight-icon">💻</div>
              <div>
                <h4 class="about-highlight-title">Web Systems &amp; E-Commerce Development</h4>
                <p class="about-highlight-desc">Architecting procedural and OOP web architectures using PHP, MySQL, JavaScript, and responsive CSS. Demonstrated in <strong>Tapseafood</strong> with session shopping carts and order workflows.</p>
                <div class="curriculum-tag">Coursework: IT 301 &amp; Web Development Project</div>
              </div>
            </div>

            <div class="about-highlight-item">
              <div class="about-highlight-icon">🗄️</div>
              <div>
                <h4 class="about-highlight-title">Relational Database Modeling &amp; Administration</h4>
                <p class="about-highlight-desc">Designing 3NF normalized schemas, primary/foreign key relations, indexed lookups, and parameterized PDO queries. Demonstrated in <strong>Hotel Online Booking</strong>.</p>
                <div class="curriculum-tag">Coursework: IT 204 &amp; Database Management Systems</div>
              </div>
            </div>

            <div class="about-highlight-item">
              <div class="about-highlight-icon">⚡</div>
              <div>
                <h4 class="about-highlight-title">Embedded Microcontrollers &amp; IoT Hardware</h4>
                <p class="about-highlight-desc">Programming Arduino Uno in C++, wiring HC-SR04 ultrasonic distance sensors, 5V relays, 12V solenoids, and streaming telemetry to MySQL. Demonstrated in <strong>Arduino Water Dispenser</strong>.</p>
                <div class="curriculum-tag">Coursework: IT 402 &amp; Embedded Systems Project</div>
              </div>
            </div>

            <div class="about-highlight-item">
              <div class="about-highlight-icon">📐</div>
              <div>
                <h4 class="about-highlight-title">Systems Analysis &amp; Software Engineering</h4>
                <p class="about-highlight-desc">Developing Software Requirement Specifications (SRS), Entity-Relationship Diagrams (ERD), Data Flow Diagrams (DFDs), and managing iterative development through Git/GitHub.</p>
                <div class="curriculum-tag">Coursework: IT 303 &amp; Systems Analysis and Design</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PROJECTS SECTION (THE 3 CORE PROJECTS) -->
    <section class="projects-section" id="projects">
      <div class="container">
        <div class="section-header">
          <div class="student-badge-pill">Academic &amp; Term Projects</div>
          <h2 class="section-title">The Three <span class="gold-gradient-text">Flagship Projects</span></h2>
          <div class="gold-divider-bar"></div>
          <p class="section-subtitle" style="margin-top: 1.25rem;">
            Full-stack academic systems engineered by John Carlo Camcam to address practical real-world requirements.
          </p>
        </div>

        <!-- Filter Pills -->
        <div class="projects-filter-bar">
          <button class="filter-pill active" data-filter="all">All Projects (3)</button>
          <button class="filter-pill" data-filter="Food">Web &amp; E-Commerce (Tapseafood)</button>
          <button class="filter-pill" data-filter="Hospitality">Reservation System (Hotel)</button>
          <button class="filter-pill" data-filter="IoT">IoT &amp; Embedded (Arduino)</button>
        </div>

        <!-- 3-Column Projects Grid -->
        <div class="projects-grid">
          <?php foreach ($projects as $project): ?>
            <article class="project-card" 
                     data-badge="<?= htmlspecialchars($project['badge']) ?>"
                     data-category="<?= htmlspecialchars($project['category']) ?>"
                     data-title="<?= htmlspecialchars($project['title']) ?>"
                     data-image="<?= htmlspecialchars($project['image_url']) ?>"
                     data-short-desc="<?= htmlspecialchars($project['short_desc']) ?>"
                     data-full-desc="<?= htmlspecialchars($project['full_desc']) ?>"
                     data-features="<?= htmlspecialchars($project['features']) ?>"
                     data-tech="<?= htmlspecialchars($project['technologies']) ?>"
                     data-demo="<?= htmlspecialchars($project['demo_link']) ?>"
                     data-github="<?= htmlspecialchars($project['github_link']) ?>">
              
              <!-- 16:9 Thumbnail -->
              <div class="project-thumbnail-box">
                <img src="<?= htmlspecialchars($project['image_url']) ?>" 
                     alt="<?= htmlspecialchars($project['title']) ?>" 
                     class="project-thumbnail-img" 
                     loading="lazy">
                
                <span class="project-type-tag">
                  <?= htmlspecialchars(str_ireplace(['capstone project', 'capstone thesis', 'capstone'], ['Project', 'Project', 'Project'], $project['project_type'] ?? 'Project')) ?>
                </span>

                <span class="project-tier-pill">
                  <?= htmlspecialchars($project['badge']) ?>
                </span>
              </div>

              <!-- Content Body -->
              <div class="project-card-body">
                <div class="project-category-meta">
                  <span class="project-cat-name"><?= htmlspecialchars($project['category']) ?></span>
                  <span class="project-order-tag">PROJECT 0<?= htmlspecialchars($project['display_order'] ?? $project['id']) ?></span>
                </div>

                <h3 class="project-card-title"><?= htmlspecialchars($project['title']) ?></h3>
                
                <p class="project-card-desc">
                  <?= htmlspecialchars($project['short_desc']) ?>
                </p>

                <!-- Project Highlights Checklist -->
                <div class="project-features-preview">
                  <?php 
                    $feats = explode(';', $project['features']);
                    foreach (array_slice($feats, 0, 3) as $fItem): 
                      if (trim($fItem)):
                  ?>
                    <div class="feature-preview-item">
                      <span class="feat-bullet">✦</span>
                      <span><?= htmlspecialchars(trim($fItem)) ?></span>
                    </div>
                  <?php endif; endforeach; ?>
                </div>

                <!-- Tech Badges -->
                <div class="project-tech-badges">
                  <?php 
                    $techArray = explode(',', $project['technologies']);
                    foreach (array_slice($techArray, 0, 4) as $index => $techItem): 
                  ?>
                    <span class="tech-pill <?= $index < 2 ? 'primary' : '' ?>">
                      <?= htmlspecialchars(trim($techItem)) ?>
                    </span>
                  <?php endforeach; ?>
                </div>

                <!-- Action Buttons -->
                <div class="project-card-actions">
                  <a href="<?= htmlspecialchars($project['demo_link']) ?>" class="btn btn-gold btn-sm" title="Launch Interactive Live Demo">
                    <span>🚀 Live Demo</span>
                  </a>
                  <button class="btn btn-outline-gold btn-sm open-modal-btn" title="View Full Project Specifications">
                    <span>✦ Specs</span>
                  </button>
                  <a href="<?= htmlspecialchars($project['github_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-silver btn-sm" title="View Source Code Repository">
                    <span>GitHub</span>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- MOCK INTERVIEW & PROFESSIONAL READINESS SHOWCASE -->
    <section class="interview-section" id="interview">
      <div class="container">
        <div class="section-header">
          <div class="student-badge-pill">Professional Readiness • Industry Immersion</div>
          <h2 class="section-title">Mock Technical &amp; <span class="gold-gradient-text">HR Interview</span></h2>
          <div class="gold-divider-bar"></div>
          <p class="section-subtitle" style="margin-top: 1.25rem;">
            A simulated workplace interview demonstrating communication confidence, technical articulation, and candidate readiness for immediate 500-hour OJT deployment.
          </p>
        </div>

        <div class="interview-grid">
          <!-- Left: Video Player Showcase Card -->
          <div class="interview-player-card">
            <!-- Studio Video Window Frame -->
            <div class="video-window-frame">
              <div class="video-top-bar">
                <div class="video-window-controls">
                  <span class="v-dot red"></span>
                  <span class="v-dot yellow"></span>
                  <span class="v-dot green"></span>
                </div>
                <div class="video-stream-meta">
                  <span class="rec-indicator"><span class="rec-pulse"></span> REC</span>
                  <span class="video-title-tag">MOCK_INTERVIEW_JOHN_CARLO_CAMCAM.MP4</span>
                </div>
                <div class="video-badge-hd">HD 1080P</div>
              </div>

              <!-- Video Player -->
              <div class="video-element-wrapper">
                <video id="mockInterviewVideo" class="interview-video-player" controls preload="metadata" playsinline poster="assets/images/john_carlo_camcam.png">
                  <source src="camcam.mp4" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>

              <div class="video-control-subtext">
                <div class="video-meta-info">
                  <span class="meta-speaker">Candidate: <strong><?= htmlspecialchars($profile['full_name']) ?></strong></span>
                  <span class="meta-dot">•</span>
                  <span class="meta-role">BSIT Candidate for OJT</span>
                </div>
                <div class="video-actions-quick">
                  <button class="btn-video-action" id="togglePlayVideoBtn" type="button">
                    <span>▶ Play Video</span>
                  </button>
                  <button class="btn-video-action" id="fullscreenVideoBtn" type="button">
                    <span>⛶ Fullscreen</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: Interview Competencies & Candidate Matrix -->
          <div class="interview-info-panel">
            <div class="interview-score-card">
              <div class="interview-score-header">
                <div>
                  <span class="score-badge">ASSESSMENT MATRIX</span>
                  <h3 class="score-title gold-gradient-text">Candidate Communication Profile</h3>
                </div>
                <div class="readiness-grade-box">
                  <span class="grade-label">OJT READY</span>
                  <span class="grade-score gold-gradient-text">98%</span>
                </div>
              </div>

              <p class="interview-summary-text">
                This mock interview recording provides recruiters and hiring managers an authentic demonstration of John Carlo's ability to communicate technical problem solving, articulate project workflows, and interact professionally in enterprise work environments.
              </p>

              <!-- Competencies Grid -->
              <div class="competencies-grid">
                <div class="competency-card">
                  <div class="comp-icon-box">🎙️</div>
                  <div class="comp-details">
                    <h4 class="comp-name">Verbal Articulation</h4>
                    <p class="comp-desc">Crisp presentation of educational background, career goals, and proactive work ethic.</p>
                  </div>
                </div>

                <div class="competency-card">
                  <div class="comp-icon-box">🧠</div>
                  <div class="comp-details">
                    <h4 class="comp-name">Technical Defense</h4>
                    <p class="comp-desc">Confident explanation of PHP/MySQL logic, database normalization, and Arduino circuits.</p>
                  </div>
                </div>

                <div class="competency-card">
                  <div class="comp-icon-box">🤝</div>
                  <div class="comp-details">
                    <h4 class="comp-name">Workplace Demeanor</h4>
                    <p class="comp-desc">Composed posture, professional demeanor, and high receptiveness to feedback and growth.</p>
                  </div>
                </div>

                <div class="competency-card">
                  <div class="comp-icon-box">⚡</div>
                  <div class="comp-details">
                    <h4 class="comp-name">STAR Methodology</h4>
                    <p class="comp-desc">Structured answers detailing Situation, Task, Action, and Measurable Project Result.</p>
                  </div>
                </div>
              </div>

              <!-- Quick CTA Bar -->
              <div class="interview-cta-bar">
                <a href="#contact" class="btn btn-gold btn-sm">
                  <span>Schedule Technical Interview</span>
                </a>
                <button class="btn btn-silver btn-sm" id="interviewResumeBtn" data-action="open-resume" onclick="openResumeModal(event)" title="Click to view Student Resume as a popup modal">
                  <span>📄 Student Resume</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CONTACT & OJT RECRUITMENT SECTION -->
    <section class="contact-section" id="contact">
      <div class="container">
        <div class="section-header">
          <div class="student-badge-pill">Internship &amp; OJT Recruitment</div>
          <h2 class="section-title">Connect with <span class="gold-gradient-text">John Carlo Camcam</span></h2>
          <div class="gold-divider-bar"></div>
          <p class="section-subtitle" style="margin-top: 1.25rem;">
            Are you an internship coordinator, company recruiter, or engineering team lead? Send a message directly to MySQL.
          </p>
        </div>

        <div class="contact-grid">
          <!-- Left: Student Candidate Profile -->
          <div class="contact-info-panel">
            <h3 class="contact-panel-title gold-gradient-text">Candidate Credentials</h3>
            <p class="contact-panel-desc">
              I am actively seeking an <strong>IT Internship (OJT)</strong> or <strong>Junior Developer</strong> opportunity. Open for on-site, hybrid, or remote deployment.
            </p>

            <div class="ojt-candidate-meta-card">
              <div class="meta-row">
                <span class="meta-label">DEGREE:</span>
                <span class="meta-value gold">BS in Information Technology (BSIT)</span>
              </div>
              <div class="meta-row">
                <span class="meta-label">PRACTICUM REQUIREMENT:</span>
                <span class="meta-value silver">486 – 500 Hours OJT</span>
              </div>
              <div class="meta-row">
                <span class="meta-label">PREFERRED ROLES:</span>
                <span class="meta-value">Web Developer (PHP/MySQL), Systems Analyst, IoT Associate</span>
              </div>
              <div class="meta-row">
                <span class="meta-label">STATUS:</span>
                <span class="meta-value" style="color: #4ade80;">🟢 Ready for Immediate Endorsement</span>
              </div>
            </div>

            <div class="contact-details-stack">
              <div class="contact-detail-row">
                <div class="contact-icon-frame">✉️</div>
                <div>
                  <div class="contact-field-sub">OFFICIAL EMAIL</div>
                  <div class="contact-field-main"><?= htmlspecialchars($profile['email']) ?></div>
                </div>
              </div>

              <div class="contact-detail-row">
                <div class="contact-icon-frame">📞</div>
                <div>
                  <div class="contact-field-sub">MOBILE CONTACT</div>
                  <div class="contact-field-main"><?= htmlspecialchars($profile['phone']) ?></div>
                </div>
              </div>

              <div class="contact-detail-row">
                <div class="contact-icon-frame">📍</div>
                <div>
                  <div class="contact-field-sub">LOCATION</div>
                  <div class="contact-field-main"><?= htmlspecialchars($profile['location']) ?></div>
                </div>
              </div>
            </div>

            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
              <a href="<?= htmlspecialchars($profile['github_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-silver btn-sm">GitHub Profile</a>
              <a href="<?= htmlspecialchars($profile['linkedin_link']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-silver btn-sm">LinkedIn Profile</a>
              <button class="btn btn-outline-gold btn-sm" id="contactModalResumeBtn" data-action="open-resume" onclick="openResumeModal(event)" title="Click to view Student Resume as a popup modal">
                <span>📄 Student Resume</span>
              </button>
            </div>
          </div>

          <!-- Right: Direct Message Form -->
          <div class="contact-form-panel">
            <?php if ($formStatus === 'success'): ?>
              <div class="form-alert success">
                ✓ <?= $formMessage ?>
              </div>
            <?php elseif ($formStatus === 'error'): ?>
              <div class="form-alert error">
                ✕ <?= $formMessage ?>
              </div>
            <?php endif; ?>

            <form action="#contact" method="POST" id="contactForm">
              <div class="form-group-item">
                <label for="sender_name" class="form-input-label">Your Name / Organization *</label>
                <input type="text" id="sender_name" name="sender_name" class="form-input-field" placeholder="e.g. Maria Santos / Tech Recruiter" required value="<?= htmlspecialchars($_POST['sender_name'] ?? '') ?>">
              </div>

              <div class="form-group-item">
                <label for="sender_email" class="form-input-label">Your Email Address *</label>
                <input type="email" id="sender_email" name="sender_email" class="form-input-field" placeholder="e.g. recruiter@company.com" required value="<?= htmlspecialchars($_POST['sender_email'] ?? '') ?>">
              </div>

              <div class="form-group-item">
                <label for="subject" class="form-input-label">Subject / Position *</label>
                <input type="text" id="subject" name="subject" class="form-input-field" placeholder="e.g. IT Internship (OJT) Opportunity / Project Inquiry" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
              </div>

              <div class="form-group-item">
                <label for="message" class="form-input-label">Message Details *</label>
                <textarea id="message" name="message" class="form-input-field form-textarea" placeholder="Enter company details, required tech stack, or interview schedule..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
              </div>

              <button type="submit" name="submit_contact" value="1" class="btn btn-gold" style="width: 100%;">
                <span>✦ Submit Message</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- PROJECT DETAILS MODAL -->
  <div class="modal-overlay" id="projectModal" role="dialog" aria-modal="true">
    <div class="modal-window">
      <button class="modal-close-button" id="modalCloseBtn" aria-label="Close modal">&times;</button>
      
      <div style="padding: 2.25rem;">
        <div class="modal-img-wrapper">
          <img src="" alt="Project Stage Preview" id="modalImage" class="modal-img-element">
        </div>

        <div id="modalCategory" class="modal-cat-tag">Category</div>
        <h3 id="modalTitle" class="gold-gradient-text" style="font-size: 1.85rem; margin-bottom: 1rem;">Project Title</h3>
        
        <p id="modalDesc" style="color: var(--silver-metallic); line-height: 1.7; margin-bottom: 1.5rem; font-size: 1rem;"></p>

        <h4 style="font-size: 1.1rem; color: var(--gold-light); margin-bottom: 0.75rem;">Key Technical Features:</h4>
        <ul id="modalFeatures" class="modal-features-list"></ul>

        <h4 style="font-size: 1.1rem; color: var(--silver-light); margin-bottom: 0.75rem;">Technologies Used:</h4>
        <div id="modalTechStack" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 2rem;"></div>

        <div style="display: flex; gap: 0.85rem; flex-wrap: wrap;">
          <a href="#" id="modalDemoLink" class="btn btn-gold">
            <span>🚀 Launch Live Demo</span>
          </a>
          <a href="#" id="modalGithubLink" target="_blank" rel="noopener noreferrer" class="btn btn-silver">
            <span>Inspect GitHub Repository</span>
          </a>
          <button class="btn btn-silver" onclick="document.getElementById('modalCloseBtn').click();">
            <span>Close Overview</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- STUDENT CV / RESUME MODAL (IMAGE POPUP) -->
  <div class="modal-overlay" id="resumeModal" role="dialog" aria-modal="true">
    <div class="modal-window resume-window resume-image-modal-window">
      <button class="modal-close-button" id="resumeCloseBtn" onclick="closeResumeModal(event)" aria-label="Close modal">&times;</button>
      
      <div class="resume-sheet resume-image-sheet">
        <!-- Resume Modal Header Actions Bar -->
        <div class="resume-modal-top-bar">
          <div class="resume-modal-title-wrap">
            <h3 class="resume-modal-heading gold-gradient-text">Student Resume (CV)</h3>
            <span class="resume-modal-sub"><?= htmlspecialchars($profile['full_name']) ?> • BS Information Technology</span>
          </div>

          <div class="resume-modal-actions">
            <button class="btn btn-silver btn-sm" id="resumeZoomBtn" type="button" onclick="toggleResumeZoom(event)" title="Toggle Zoom In/Out">
              <span>🔍 Zoom</span>
            </button>
            <a href="<?= htmlspecialchars($resumeImageSrc) ?>" download="student_resume.png" class="btn btn-silver btn-sm" title="Download Resume Image">
              <span>⬇ Download</span>
            </a>
            <button class="btn btn-gold btn-sm" onclick="window.print();" title="Print Resume">
              <span>🖨️ Print</span>
            </button>
          </div>
        </div>

        <!-- Student Resume Image Showcase -->
        <div class="resume-image-container" id="resumeImgContainer">
          <img src="<?= htmlspecialchars($resumeImageSrc) ?>" 
               onerror="if (this.src.indexOf('student_resume.png') === -1) { this.src='assets/images/student_resume.png'; }" 
               alt="<?= htmlspecialchars($profile['full_name']) ?> - Student Resume" 
               class="resume-full-image" 
               id="studentResumeImg" 
               onclick="toggleResumeZoom(event)" 
               title="Click to toggle zoom">
        </div>

        <div class="resume-modal-footer">
          <span style="font-size: 0.82rem; color: var(--silver-dark); font-family: var(--font-mono);">Official Student Resume • BS Information Technology</span>
          <button class="btn btn-silver btn-sm" onclick="closeResumeModal(event)">
            <span>Close Resume</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- LOGIN MODAL (USERNAME: camcam / PASSWORD: camcam12345) -->
  <div class="modal-overlay <?= $loginStatus === 'error' ? 'active' : '' ?>" id="loginModal" role="dialog" aria-modal="true">
    <div class="modal-window login-modal-window">
      <button class="modal-close-button" id="loginCloseBtn" aria-label="Close modal">&times;</button>
      
      <div class="login-modal-sheet">
        <div class="login-brand-crest">
          <span class="crest-initials">JCC</span>
        </div>
        
        <h3 class="login-modal-title gold-gradient-text">Student Developer Login</h3>
        <p class="login-modal-subtitle">Authorized access for John Carlo Camcam to manage inquiries and system data.</p>

        <!-- Credentials hint pill -->
        <div class="login-credentials-hint">
          <span class="hint-icon">💡</span>
          <div>
            <strong>Credentials:</strong> User: <code class="gold-code">camcam</code> &bull; Password: <code class="gold-code">camcam12345</code>
          </div>
        </div>

        <?php if ($loginStatus === 'error'): ?>
          <div class="form-alert error" style="margin-bottom: 1.25rem;">
            ✕ <?= htmlspecialchars($loginMessage) ?>
          </div>
        <?php elseif ($loginStatus === 'success'): ?>
          <div class="form-alert success" style="margin-bottom: 1.25rem;">
            ✓ <?= htmlspecialchars($loginMessage) ?>
          </div>
        <?php endif; ?>

        <form action="index.php" method="POST" id="loginForm" class="login-form">
          <div class="form-group-item">
            <label for="login_username" class="form-input-label">Username</label>
            <div class="input-with-icon">
              <span class="input-icon">👤</span>
              <input type="text" id="login_username" name="username" class="form-input-field" placeholder="Enter username (camcam)" required autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? 'camcam') ?>">
            </div>
          </div>

          <div class="form-group-item">
            <label for="login_password" class="form-input-label">Password</label>
            <div class="input-with-icon">
              <span class="input-icon">🔑</span>
              <input type="password" id="login_password" name="password" class="form-input-field" placeholder="Enter password (camcam12345)" required autocomplete="current-password" value="<?= htmlspecialchars($_POST['password'] ?? 'camcam12345') ?>">
            </div>
          </div>

          <button type="submit" name="submit_login" value="1" class="btn btn-gold btn-block" style="width: 100%; margin-top: 0.5rem;">
            <span>✦ Log In to Portal</span>
          </button>
        </form>

        <div class="login-modal-footer">
          <span class="footer-note">Authenticated with PHP 8 &amp; MySQL RDBMS</span>
        </div>
      </div>
    </div>
  </div>

  <?php if ($isLoggedIn): ?>
  <!-- ADMIN PORTAL / MESSAGES INBOX MODAL -->
  <div class="modal-overlay" id="adminModal" role="dialog" aria-modal="true">
    <div class="modal-window admin-modal-window">
      <button class="modal-close-button" id="adminCloseBtn" aria-label="Close modal">&times;</button>
      
      <div class="admin-modal-sheet">
        <div class="admin-header-row">
          <div>
            <div class="admin-badge">ADMINISTRATIVE DASHBOARD</div>
            <h3 class="admin-title gold-gradient-text">Welcome back, <?= htmlspecialchars($currentUser) ?>!</h3>
            <p class="admin-subtitle">Live MySQL Contact Form Inquiries &amp; Recruiter Messages (<?= count($adminMessages) ?> Total)</p>
          </div>
          <div class="admin-header-actions">
            <a href="index.php?logout=1" class="btn btn-silver btn-sm">
              <span>🚪 Logout</span>
            </a>
          </div>
        </div>

        <div class="admin-divider"></div>

        <!-- Messages List -->
        <div class="admin-messages-list">
          <?php if (empty($adminMessages)): ?>
            <div class="no-messages-box">
              <span style="font-size: 2rem;">📭</span>
              <h4>No Inquiries Recorded Yet</h4>
              <p>Submitted messages through the OJT Inquiry form will appear here in real-time from MySQL.</p>
            </div>
          <?php else: ?>
            <?php foreach ($adminMessages as $msg): ?>
              <div class="admin-message-card">
                <div class="message-card-top">
                  <div>
                    <strong class="msg-sender-name"><?= htmlspecialchars($msg['sender_name']) ?></strong>
                    <span class="msg-sender-email">&lt;<?= htmlspecialchars($msg['sender_email']) ?>&gt;</span>
                  </div>
                  <div class="msg-date"><?= htmlspecialchars($msg['created_at']) ?></div>
                </div>
                <div class="msg-subject">
                  <span class="subject-tag">SUBJECT:</span> <?= htmlspecialchars($msg['subject']) ?>
                </div>
                <div class="msg-body">
                  <?= nl2br(htmlspecialchars($msg['message'])) ?>
                </div>
                <div class="msg-actions">
                  <a href="mailto:<?= htmlspecialchars($msg['sender_email']) ?>?subject=Re:%20<?= urlencode($msg['subject']) ?>" class="btn btn-gold btn-sm">
                    <span>✉ Reply via Email</span>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="admin-footer-bar">
          <button class="btn btn-silver btn-sm" onclick="document.getElementById('adminCloseBtn').click();">
            <span>Close Dashboard</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- MODERN FOOTER -->
  <footer class="footer">
    <div class="container">
      <div class="footer-columns">
        <div>
          <div class="brand-link" style="margin-bottom: 1.25rem;">
            <div class="brand-crest">
              <span class="crest-initials">JCC</span>
            </div>
            <div class="brand-meta">
              <span class="brand-name"><span class="gold-gradient-text"><?= htmlspecialchars($profile['full_name']) ?></span></span>
              <span class="brand-subtitle">BS in Information Technology</span>
            </div>
          </div>
          <p style="color: var(--silver-dark); font-size: 0.95rem; line-height: 1.7; max-width: 360px;">
            Student IT Portfolio showcasing projects in full-stack PHP &amp; MySQL and embedded Arduino IoT systems. Ready for industry internship.
          </p>
        </div>

        <div>
          <h4 class="footer-heading">Projects</h4>
          <ul class="footer-links">
            <li><a href="#projects" class="footer-link-item">Tapseafood: Online Food Ordering</a></li>
            <li><a href="#projects" class="footer-link-item">Hotel Online Booking</a></li>
            <li><a href="#projects" class="footer-link-item">Arduino Water Dispenser</a></li>

          </ul>
        </div>

        <div>
          <h4 class="footer-heading">Quick Links</h4>
          <ul class="footer-links">
            <li><a href="#hero" class="footer-link-item">Top of Page</a></li>
            <li><a href="#academic" class="footer-link-item">Academic Journey</a></li>
            <li><a href="#interview" class="footer-link-item">Mock Interview Video</a></li>
            <li><a href="#" class="footer-link-item" id="footerResumeBtn" data-action="open-resume" onclick="openResumeModal(event)"><span>📄 Student Resume</span></a></li>
            <?php if ($isLoggedIn): ?>
              <li><a href="#" class="footer-link-item" id="footerAdminBtn">Admin Portal (👑 <?= htmlspecialchars($currentUser) ?>)</a></li>
              <li><a href="index.php?logout=1" class="footer-link-item">Log Out</a></li>
            <?php else: ?>
              <li><a href="#" class="footer-link-item" id="footerLoginBtn">Admin Login (camcam)</a></li>
            <?php endif; ?>
            <li><a href="database.sql" download="database.sql" class="footer-link-item">Download database.sql</a></li>
            <li><a href="<?= htmlspecialchars($profile['github_link']) ?>" target="_blank" rel="noopener noreferrer" class="footer-link-item">GitHub Profile</a></li>
            <li><a href="#contact" class="footer-link-item">Contact / OJT Inquiry</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom-bar">
        <div>&copy; <?= date('Y') ?> <strong style="color: var(--gold-light);"><?= htmlspecialchars($profile['full_name']) ?></strong>. BSIT Student Portfolio.</div>
        <div>Engineered with <span class="gold-gradient-text">PHP &amp; MySQL</span> • Gold &amp; Silver Edition</div>
      </div>
    </div>
  </footer>

  <script src="assets/js/main.js?v=<?= file_exists(__DIR__ . '/assets/js/main.js') ? filemtime(__DIR__ . '/assets/js/main.js') : time() ?>"></script>

  <!-- Direct Inline Modal Controller (Guarantees Student Resume Modal Always Opens) -->
  <script>
    window.openResumeModal = function(e) {
      if (e) {
        if (e.preventDefault) e.preventDefault();
        if (e.stopPropagation) e.stopPropagation();
      }
      var modal = document.getElementById('resumeModal');
      if (modal) {
        modal.style.display = 'flex';
        modal.style.opacity = '1';
        modal.style.visibility = 'visible';
        modal.style.pointerEvents = 'auto';
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    };

    window.closeResumeModal = function(e) {
      if (e && e.preventDefault) e.preventDefault();
      var modal = document.getElementById('resumeModal');
      if (modal) {
        modal.classList.remove('active');
        modal.style.opacity = '0';
        modal.style.visibility = 'hidden';
        modal.style.pointerEvents = 'none';
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        var img = document.getElementById('studentResumeImg');
        if (img) img.classList.remove('zoomed');
        var zoomBtn = document.getElementById('resumeZoomBtn');
        if (zoomBtn) zoomBtn.innerHTML = '<span>🔍 Zoom</span>';
      }
    };

    window.toggleResumeZoom = function(e) {
      if (e && e.preventDefault) e.preventDefault();
      var img = document.getElementById('studentResumeImg');
      var zoomBtn = document.getElementById('resumeZoomBtn');
      if (img) {
        img.classList.toggle('zoomed');
        if (zoomBtn) {
          zoomBtn.innerHTML = img.classList.contains('zoomed')
            ? '<span>🔍 Zoom Out</span>'
            : '<span>🔍 Zoom In</span>';
        }
      }
    };

    // Backdrop click listener
    var rModal = document.getElementById('resumeModal');
    if (rModal) {
      rModal.addEventListener('click', function(e) {
        if (e.target === rModal) {
          window.closeResumeModal(e);
        }
      });
    }

    // Escape key listener
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        window.closeResumeModal(e);
      }
    });
  </script>
</body>
</html>
