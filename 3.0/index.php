<?php
session_start();
$user = $_SESSION['user'] ?? null;
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DriveEasy — Used Car Trading Platform</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="index">
<div class="page-wrap">

  <nav class="nav">
    <div class="nav-inner">
      <a href="index.php" class="nav-logo">DriveEasy</a>
      <ul class="nav-links">
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="search.php">Search Vehicles</a></li>
        <li><a href="seller.php">Seller Centre</a></li>
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

  <main class="page-main">

    <section class="hero">
      <div class="container">
        <p class="hero-eyebrow">Used Car Trading Platform</p>
        <h1 class="hero-title">Find Your Perfect Used Car — Fast, Simple and Trusted</h1>
        <p class="hero-subtitle">Browse thousands of verified used car listings from trusted sellers across China. Compare prices, check details and contact sellers directly.</p>
        <div class="hero-actions">
          <a href="search.php" class="btn btn-primary btn-lg">Search Vehicles</a>
          <a href="register.php" class="btn btn-secondary btn-lg">Register as a Seller</a>
        </div>
      </div>
    </section>

    <div style="border-top: 1px solid #E5E5E5;"></div>

    <section class="section">
      <div class="container">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 48px 64px;">
          <div>
            <div class="section-label">01</div>
            <div class="section-heading">Smart Vehicle Search</div>
            <p class="text-muted text-sm mt-8">Search by brand, model or year to instantly find matching listings from our growing database of used cars nationwide.</p>
          </div>
          <div>
            <div class="section-label">02</div>
            <div class="section-heading">Easy Seller Registration</div>
            <p class="text-muted text-sm mt-8">Create your seller account in minutes and start listing your vehicles. Manage all your listings from a single dashboard.</p>
          </div>
          <div>
            <div class="section-label">03</div>
            <div class="section-heading">Verified Listings Database</div>
            <p class="text-muted text-sm mt-8">All car listings are stored securely in our database with full seller details, giving buyers confidence and transparency.</p>
          </div>
        </div>
      </div>
    </section>

    <div style="border-top: 1px solid #E5E5E5;"></div>
    <section class="section">
      <div class="container">
        <div style="max-width: 680px;">
          <p class="section-label">About Us</p>
          <h2 class="section-heading" style="font-size: 20px; margin-bottom: 16px;">Connecting Car Buyers and Sellers Across China Since 2024</h2>
          <p class="text-muted" style="font-size: 15px; line-height: 1.7;">DriveEasy is an online used car trading platform designed to make buying and selling second-hand vehicles simple, transparent and efficient. Our platform connects individual sellers with buyers looking for quality pre-owned cars at fair prices. We believe in giving sellers a straightforward way to list their vehicles and giving buyers the tools they need to make confident decisions.</p>
          <p class="text-muted mt-8" style="font-size: 13px;">
            Phone: 400-888-8888 &nbsp;·&nbsp; Email: contact@driveeasy.cn &nbsp;·&nbsp; Address: 1 Tech Park Road, Hainan, China
          </p>
        </div>
      </div>
    </section>

  </main>

  <footer>
    <div class="footer-inner">
      <span class="footer-copy">© 2026 DriveEasy. All rights reserved.</span>
      <ul class="footer-links">
        <li><a href="search.php">Search Vehicles</a></li>
        <li><a href="seller.php">Seller Centre</a></li>
        <li><a href="mailto:contact@driveeasy.cn">Contact Us</a></li>
      </ul>
    </div>
  </footer>

</div>
<script src="js/validation.js"></script>
<script src="js/main.js"></script>
</body>
</html>
