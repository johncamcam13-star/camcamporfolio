<?php
/**
 * Dedicated Admin & Developer Login Portal
 * User: camcam | Password: camcam12345
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

// If already logged in, redirect to index.php
if (!empty($_SESSION['logged_user'])) {
    header('Location: index.php');
    exit;
}

$loginStatus = null;
$loginMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $res = Database::verifyLogin($username, $password);
    if ($res['success']) {
        $_SESSION['logged_user'] = $res['username'];
        $_SESSION['user_role'] = $res['role'];
        header('Location: index.php');
        exit;
    } else {
        $loginStatus = 'error';
        $loginMessage = $res['error'] ?? 'Invalid username or password.';
    }
}

$profile = Database::getProfile();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Developer Login | <?= htmlspecialchars($profile['full_name']) ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" type="image/svg+xml" href="assets/images/profile_avatar.svg">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle at 50% 30%, #101626 0%, #06080d 100%);
      padding: 1.5rem;
    }
    .standalone-login-card {
      background: #0b0f17;
      border: 1px solid var(--gold-border);
      border-radius: 20px;
      padding: 2.75rem 2.5rem;
      max-width: 450px;
      width: 100%;
      box-shadow: 0 35px 95px rgba(0, 0, 0, 0.95), var(--gold-glow);
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="standalone-login-card">
    <a href="index.php" class="brand-link" style="justify-content: center; margin-bottom: 1.5rem; display: inline-flex;">
      <div class="brand-crest">
        <span class="crest-initials">JCC</span>
      </div>
    </a>

    <h2 class="gold-gradient-text" style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.4rem;">Developer Login</h2>
    <p style="color: var(--silver-dark); font-size: 0.9rem; margin-bottom: 1.5rem;">Access the administrative portal for John Carlo Camcam.</p>

    <!-- Credentials Hint Pill -->
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
    <?php endif; ?>

    <form action="login.php" method="POST" class="login-form">
      <div class="form-group-item">
        <label for="username" class="form-input-label">Username</label>
        <div class="input-with-icon">
          <span class="input-icon">👤</span>
          <input type="text" id="username" name="username" class="form-input-field" placeholder="camcam" required autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? 'camcam') ?>">
        </div>
      </div>

      <div class="form-group-item">
        <label for="password" class="form-input-label">Password</label>
        <div class="input-with-icon">
          <span class="input-icon">🔑</span>
          <input type="password" id="password" name="password" class="form-input-field" placeholder="camcam12345" required autocomplete="current-password" value="<?= htmlspecialchars($_POST['password'] ?? 'camcam12345') ?>">
        </div>
      </div>

      <button type="submit" name="submit_login" value="1" class="btn btn-gold" style="width: 100%; margin-top: 0.75rem;">
        <span>✦ Log In to Portal</span>
      </button>
    </form>

    <div style="margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.88rem;">
      <a href="index.php" style="color: var(--gold-light); text-decoration: none;">&larr; Back to Portfolio Website</a>
    </div>
  </div>

</body>
</html>
