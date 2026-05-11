<?php
session_start();
require 'db.php';

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$user = $_SESSION['user'] ?? null;

$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: search.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT c.*, s.username AS seller_username, s.name AS seller_name
     FROM cars c
     JOIN sellers s ON s.id = c.seller_id
     WHERE c.id = ?'
);
$stmt->execute([$id]);
$car = $stmt->fetch();

$title = $car ? h($car['model']) . ' (' . (int)$car['year'] . ') — DriveEasy' : 'Vehicle Not Found — DriveEasy';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="car-detail">
<div class="page-wrap">

  <nav class="nav">
    <div class="nav-inner">
      <a href="index.php" class="nav-logo">DriveEasy</a>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="search.php" class="active">Search Vehicles</a></li>
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

  <div class="breadcrumb">
    <div class="breadcrumb-inner">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">/</span>
      <a href="search.php">Search Vehicles</a>
      <span class="breadcrumb-sep">/</span>
      <a href="javascript:history.back()">Search Results</a>
      <span class="breadcrumb-sep">/</span>
      <span class="breadcrumb-current">
        <?= $car ? h($car['model']) . ' (' . (int)$car['year'] . ')' : 'Vehicle Detail' ?>
      </span>
    </div>
  </div>

  <main class="page-main">
    <div class="container">
      <div class="car-detail-section">

        <?php if (!$car): ?>
          <div class="empty-state">
            <div class="empty-state-title">Vehicle Not Found</div>
            <div class="empty-state-desc">This listing may have been removed.</div>
            <a href="search.php" class="btn btn-secondary">Back to Search</a>
          </div>

        <?php else: ?>
          <div class="car-detail-layout">
            <div>
              <div class="car-detail-img">
                <?php if ($car['image_path']): ?>
                  <img src="<?= h($car['image_path']) ?>" alt="<?= h($car['model']) ?>"
                       onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'ph',textContent:'[Vehicle image · 800×600]'}))">
                <?php else: ?>
                  <span class="ph">[Vehicle image · 800×600]</span>
                <?php endif; ?>
              </div>
            </div>
            <div>
              <div class="car-info-model"><?= h($car['model']) ?></div>
              <div class="car-info-price">¥<?= number_format((float)$car['price'], 0, '.', ',') ?></div>
              <div class="car-info-actions">
                <a href="javascript:history.back()" class="btn btn-secondary btn-sm">← Back to List</a>
              </div>
              <div class="spec-table">
                <div class="spec-row">
                  <span class="spec-label">Year</span>
                  <span class="spec-value"><?= (int)$car['year'] ?></span>
                </div>
                <div class="spec-row">
                  <span class="spec-label">Colour</span>
                  <span class="spec-value"><?= h($car['color']) ?></span>
                </div>
                <div class="spec-row">
                  <span class="spec-label">Location</span>
                  <span class="spec-value"><?= h($car['location']) ?></span>
                </div>
                <div class="spec-row">
                  <span class="spec-label">Seller</span>
                  <span class="spec-value"><?= h($car['seller_username']) ?></span>
                </div>
                <div class="spec-row">
                  <span class="spec-label">Date Listed</span>
                  <span class="spec-value"><?= h(substr($car['created_at'], 0, 10)) ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
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
