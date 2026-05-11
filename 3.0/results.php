<?php
session_start();
require 'db.php';

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$user  = $_SESSION['user'] ?? null;
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$model = trim($_GET['model'] ?? '');
$year  = trim($_GET['year']  ?? '');

// Build query dynamically with prepared statement
$sql    = 'SELECT * FROM cars WHERE 1=1';
$params = [];

if ($model !== '') {
    $sql    .= ' AND model LIKE ?';
    $params[] = '%' . $model . '%';
}
if ($year !== '' && ctype_digit($year)) {
    $sql    .= ' AND year = ?';
    $params[] = (int)$year;
}

$sql .= ' ORDER BY created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$cars = $stmt->fetchAll();

// Heading label
$parts = [];
if ($model) $parts[] = '"' . h($model) . '"';
if ($year)  $parts[] = h($year);
$heading = $parts ? 'Results: ' . implode(', ', $parts) : 'All Vehicles';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results — DriveEasy</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="results">
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
      <span class="breadcrumb-current">Search Results</span>
    </div>
  </div>

  <main class="page-main">
    <div class="container">

      <?php if ($flash): ?>
        <div class="flash flash-<?= h($flash['type']) ?>" role="alert" style="margin-bottom: 24px;">
          <?= h($flash['msg']) ?>
        </div>
      <?php endif; ?>

      <!-- Refine search strip -->
      <div class="refine-strip">
        <form id="refine-form" method="GET" action="results.php" novalidate>
          <div class="search-row" style="max-width: 640px;">
            <div class="form-group" style="margin-bottom: 0;">
              <label class="form-label" for="model">Model</label>
              <input class="form-input" type="text" id="model" name="model"
                     placeholder="e.g. Toyota"
                     value="<?= h($model) ?>">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
              <label class="form-label" for="year">Year</label>
              <input class="form-input" type="number" id="year" name="year"
                     placeholder="e.g. 2022" min="1990" max="2026"
                     value="<?= h($year) ?>">
            </div>
            <div>
              <div style="height: 19px;"></div>
              <button type="submit" class="btn btn-secondary" style="height: 40px;">
                Refine Search
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Results meta -->
      <div class="results-meta">
        <h1 class="results-heading"><?= $heading ?></h1>
        <p class="results-count"><?= count($cars) ?> vehicle(s) found</p>
      </div>

      <!-- Car list -->
      <div class="car-list" style="padding-bottom: 80px;">
        <?php if (empty($cars)): ?>
          <div class="empty-state">
            <div class="empty-state-title">No Vehicles Found</div>
            <div class="empty-state-desc">Try a different model keyword or year.</div>
            <a href="search.php" class="btn btn-secondary">Search Again</a>
          </div>
        <?php else: ?>
          <?php foreach ($cars as $car): ?>
            <a class="car-list-item" href="car-detail.php?id=<?= (int)$car['id'] ?>">
              <div class="car-thumb">
                <?php if ($car['image_path']): ?>
                  <img src="<?= h($car['image_path']) ?>" alt="<?= h($car['model']) ?>"
                       onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'ph',textContent:'[Image]'}))">
                <?php else: ?>
                  <span class="ph">[Image]</span>
                <?php endif; ?>
              </div>
              <div class="car-list-body">
                <div class="car-list-title"><?= h($car['model']) ?> (<?= (int)$car['year'] ?>)</div>
                <div class="car-list-meta">
                  <span><?= h($car['color']) ?></span>
                  <span><?= h($car['location']) ?></span>
                  <span>Listed: <?= h(substr($car['created_at'], 0, 10)) ?></span>
                </div>
              </div>
              <div class="car-list-price">¥<?= number_format((float)$car['price'], 0, '.', ',') ?></div>
            </a>
          <?php endforeach; ?>
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
