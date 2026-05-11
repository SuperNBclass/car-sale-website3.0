<?php
session_start();
require 'db.php';

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$user   = $_SESSION['user'] ?? null;
$errors = [];
$fields = ['name' => '', 'address' => '', 'phone' => '', 'email' => '', 'username' => '', 'password' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $key => $_) {
        $fields[$key] = trim($_POST[$key] ?? '');
    }

    // Server-side validation
    if (!$fields['name'] || !preg_match('/^[A-Za-z\s]+$/', $fields['name'])) {
        $errors['name'] = 'Name can only contain letters and spaces';
    }
    if (!$fields['address'] || !preg_match('/^[A-Za-z0-9\s]+$/', $fields['address'])) {
        $errors['address'] = 'Address can only contain letters, numbers and spaces';
    }
    if (!$fields['phone'] || !preg_match('/^1[3-9]\d{9}$/', $fields['phone'])) {
        $errors['phone'] = 'Please enter a valid mobile number (11 digits, starting with 1)';
    }
    $atCount = substr_count($fields['email'], '@');
    if (!$fields['email'] || $atCount !== 1 || !preg_match('/\.(com|cn)$/i', $fields['email'])) {
        $errors['email'] = 'Email must contain exactly one @ and end with .com or .cn';
    }
    if (!$fields['username'] || !preg_match('/^[A-Za-z0-9]{6,}$/', $fields['username'])) {
        $errors['username'] = 'Username must be at least 6 alphanumeric characters';
    }
    if (!$fields['password'] || !preg_match('/^[A-Za-z0-9]{6,}$/', $fields['password'])) {
        $errors['password'] = 'Password must be at least 6 alphanumeric characters';
    }

    // Username uniqueness check
    if (!isset($errors['username'])) {
        $stmt = $pdo->prepare('SELECT id FROM sellers WHERE username = ?');
        $stmt->execute([$fields['username']]);
        if ($stmt->fetch()) {
            $errors['username'] = 'Username already taken, please choose another';
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($fields['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO sellers (name, address, phone, email, username, password) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$fields['name'], $fields['address'], $fields['phone'], $fields['email'], $fields['username'], $hashed]);

        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Registration successful! Please log in.'];
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — DriveEasy</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="register">
<div class="page-wrap">

  <nav class="nav">
    <div class="nav-inner">
      <a href="index.php" class="nav-logo">DriveEasy</a>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="search.php">Search Vehicles</a></li>
        <li><a href="seller.php" class="active">Seller Centre</a></li>
      </ul>
      <div class="nav-right">
        <?php if ($user): ?>
          <span class="user-status">
            <strong><?= h($user['username']) ?></strong>
            <a href="logout.php" class="btn btn-secondary btn-sm">Sign Out</a>
          </span>
        <?php else: ?>
          <a href="login.php" class="btn btn-secondary btn-sm">Log In</a>
          <a href="register.php" class="btn btn-primary btn-sm">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="sub-nav">
    <div class="sub-nav-inner">
      <span class="sub-nav-label">Seller Centre</span>
      <ul class="sub-nav-links">
        <li><a href="register.php" class="active">Register</a></li>
        <li><a href="login.php">Log In</a></li>
        <li><a href="add-car.php">Add Vehicle</a></li>
      </ul>
    </div>
  </div>

  <div class="breadcrumb">
    <div class="breadcrumb-inner">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">/</span>
      <a href="seller.php">Seller Centre</a>
      <span class="breadcrumb-sep">/</span>
      <span class="breadcrumb-current">Register</span>
    </div>
  </div>

  <main class="page-main">
    <div class="container">
      <div class="page-header">
        <h1>Create Account</h1>
        <p>Fill in the details below to create your seller account.</p>
      </div>

      <div class="form-wrap">

        <?php if (!empty($errors)): ?>
          <div class="flash flash-error" role="alert">Please correct the errors below and try again.</div>
        <?php endif; ?>

        <form id="register-form" method="POST" action="register.php" novalidate autocomplete="off">

          <div class="form-group">
            <label class="form-label" for="name">
              Name <span class="text-muted fw-400" style="font-weight:400">(letters and spaces only)</span>
            </label>
            <input class="form-input <?= isset($errors['name']) ? 'is-error' : '' ?>"
                   type="text" id="name" name="name"
                   placeholder="e.g. Zhang Wei"
                   value="<?= h($fields['name']) ?>" autocomplete="off">
            <span class="form-error" id="name-error"><?= h($errors['name'] ?? '') ?></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="address">
              Address <span class="text-muted" style="font-weight:400">(letters, numbers and spaces only)</span>
            </label>
            <input class="form-input <?= isset($errors['address']) ? 'is-error' : '' ?>"
                   type="text" id="address" name="address"
                   placeholder="e.g. 123 Main Street Beijing"
                   value="<?= h($fields['address']) ?>" autocomplete="off">
            <span class="form-error" id="address-error"><?= h($errors['address'] ?? '') ?></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="phone">Phone Number</label>
            <input class="form-input <?= isset($errors['phone']) ? 'is-error' : '' ?>"
                   type="tel" id="phone" name="phone"
                   placeholder="1xxxxxxxxxx"
                   value="<?= h($fields['phone']) ?>" autocomplete="off" maxlength="11">
            <span class="form-hint">Chinese mobile number, 11 digits, starting with 1</span>
            <span class="form-error" id="phone-error"><?= h($errors['phone'] ?? '') ?></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input class="form-input <?= isset($errors['email']) ? 'is-error' : '' ?>"
                   type="email" id="email" name="email"
                   placeholder="name@example.com"
                   value="<?= h($fields['email']) ?>" autocomplete="off">
            <span class="form-hint">Must contain exactly one @ and end with .com or .cn</span>
            <span class="form-error" id="email-error"><?= h($errors['email'] ?? '') ?></span>
          </div>

          <div style="border-top: 1px solid #E5E5E5; margin: 28px 0;"></div>

          <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input class="form-input <?= isset($errors['username']) ? 'is-error' : '' ?>"
                   type="text" id="username" name="username"
                   placeholder="At least 6 characters"
                   value="<?= h($fields['username']) ?>" autocomplete="off">
            <span class="form-hint">At least 6 alphanumeric characters, no special characters</span>
            <span class="form-error" id="username-error"><?= h($errors['username'] ?? '') ?></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input class="form-input <?= isset($errors['password']) ? 'is-error' : '' ?>"
                   type="password" id="password" name="password"
                   placeholder="At least 6 characters" autocomplete="new-password">
            <span class="form-hint">At least 6 alphanumeric characters, no special characters</span>
            <span class="form-error" id="password-error"><?= h($errors['password'] ?? '') ?></span>
          </div>

          <div class="mt-32">
            <button type="submit" class="btn btn-primary btn-lg btn-block">Create Account</button>
          </div>

          <p class="text-sm text-muted mt-16">
            Already have an account? <a href="login.php">Log in</a>
          </p>

        </form>
      </div>
    </div>
  </main>

  <footer>
    <div class="footer-inner">
      <span class="footer-copy">© 2026 DriveEasy. All rights reserved.</span>
      <ul class="footer-links">
        <li><a href="search.php">Search Vehicles</a></li>
        <li><a href="mailto:contact@driveeasy.cn">Contact Us</a></li>
      </ul>
    </div>
  </footer>

</div>
<script src="js/validation.js"></script>
<script src="js/main.js"></script>
</body>
</html>
