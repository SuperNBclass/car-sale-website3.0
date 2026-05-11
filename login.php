<?php
session_start();
require 'db.php';

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Already logged in
if (isset($_SESSION['user'])) {
    header('Location: seller.php');
    exit;
}

$errors   = [];
$username = '';

// Consume flash from register redirect
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username) $errors['username'] = 'This field is required';
    if (!$password) $errors['password'] = 'This field is required';

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM sellers WHERE username = ?');
        $stmt->execute([$username]);
        $seller = $stmt->fetch();

        if (!$seller || !password_verify($password, $seller['password'])) {
            $flash = ['type' => 'error', 'msg' => 'Incorrect username or password, please try again.'];
        } else {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'       => $seller['id'],
                'username' => $seller['username'],
                'name'     => $seller['name'],
            ];
            $redirect = $_GET['redirect'] ?? 'seller.php';
            header('Location: ' . $redirect);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In — DriveEasy</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="login">
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
        <a href="login.php" class="btn btn-secondary btn-sm">Log In</a>
        <a href="register.php" class="btn btn-primary btn-sm">Register</a>
      </div>
    </div>
  </nav>

  <div class="sub-nav">
    <div class="sub-nav-inner">
      <span class="sub-nav-label">Seller Centre</span>
      <ul class="sub-nav-links">
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php" class="active">Log In</a></li>
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
      <span class="breadcrumb-current">Log In</span>
    </div>
  </div>

  <main class="page-main">
    <div class="container">
      <div class="page-header">
        <h1>Log In</h1>
        <p>Sign in to your seller account to manage your listings.</p>
      </div>

      <div class="form-wrap-sm">

        <?php if ($flash): ?>
          <div class="flash flash-<?= h($flash['type']) ?>" role="alert"><?= h($flash['msg']) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['required'])): ?>
          <div class="flash flash-error" role="alert">Please log in before adding a vehicle.</div>
        <?php endif; ?>

        <form id="login-form" method="POST" action="login.php<?= isset($_GET['redirect']) ? '?redirect=' . h($_GET['redirect']) : '' ?>" novalidate autocomplete="off">

          <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input class="form-input <?= isset($errors['username']) ? 'is-error' : '' ?>"
                   type="text" id="username" name="username"
                   placeholder="Enter your username"
                   value="<?= h($username) ?>" autocomplete="username">
            <span class="form-error" id="username-error"><?= h($errors['username'] ?? '') ?></span>
          </div>

          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input class="form-input <?= isset($errors['password']) ? 'is-error' : '' ?>"
                   type="password" id="password" name="password"
                   placeholder="Enter your password" autocomplete="current-password">
            <span class="form-error" id="password-error"><?= h($errors['password'] ?? '') ?></span>
          </div>

          <div class="mt-28" style="margin-top: 28px;">
            <button type="submit" class="btn btn-primary btn-lg btn-block">Log In</button>
          </div>

          <p class="text-sm text-muted mt-16">
            Don't have an account? <a href="register.php">Register now</a>
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
