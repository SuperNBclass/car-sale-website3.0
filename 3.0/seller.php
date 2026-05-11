<?php
session_start();
require 'db.php';

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Centre — DriveEasy</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="seller">
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
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Log In</a></li>
        <li><a href="add-car.php">Add Vehicle</a></li>
      </ul>
    </div>
  </div>

  <div class="breadcrumb">
    <div class="breadcrumb-inner">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">/</span>
      <span class="breadcrumb-current">Seller Centre</span>
    </div>
  </div>

  <main class="page-main">
    <div class="container">
      <div class="page-header">
        <h1>Seller Centre</h1>
        <p>List your used car and reach potential buyers.</p>
      </div>

      <p class="text-sm text-muted" id="seller-login-status">
        <?php if ($user): ?>
          Logged in as <strong><?= h($user['username']) ?></strong>.
          <a href="logout.php">Sign Out</a>
        <?php else: ?>
          Not logged in. <a href="login.php">Log in now</a> to manage your vehicles.
        <?php endif; ?>
      </p>

      <div class="seller-cards">
        <a href="register.php" class="seller-card">
          <div class="seller-card-icon"></div>
          <div class="seller-card-title">Create Account</div>
          <div class="seller-card-desc">Create a seller account by filling in your name, contact details and login credentials.</div>
        </a>
        <a href="login.php" class="seller-card">
          <div class="seller-card-icon"></div>
          <div class="seller-card-title">Log In</div>
          <div class="seller-card-desc">Already have an account? Log in with your username and password to manage your listings.</div>
        </a>
        <a href="add-car.php" class="seller-card">
          <div class="seller-card-icon"></div>
          <div class="seller-card-title">Add Vehicle</div>
          <div class="seller-card-desc">Fill in the model, year, colour, price and other details, upload an image, and publish your listing.</div>
        </a>
      </div>

      <div class="section">
        <div style="border-top: 1px solid #E5E5E5; padding-top: 48px; max-width: 600px;">
          <p class="section-label">How It Works</p>
          <ol style="padding-left: 20px; display: flex; flex-direction: column; gap: 12px; margin-top: 16px;">
            <li class="text-sm" style="line-height: 1.6; color: #737373;">
              <strong style="color: #000;">Step 1</strong> — Go to the Register page and create your account.
            </li>
            <li class="text-sm" style="line-height: 1.6; color: #737373;">
              <strong style="color: #000;">Step 2</strong> — Go to the Log In page and sign in with your credentials.
            </li>
            <li class="text-sm" style="line-height: 1.6; color: #737373;">
              <strong style="color: #000;">Step 3</strong> — Go to Add Vehicle, fill in the details and publish your listing.
            </li>
          </ol>
        </div>
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
