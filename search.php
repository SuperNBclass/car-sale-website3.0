<?php
session_start();
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Vehicles — DriveEasy</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="search">
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
      <span class="breadcrumb-current">Search Vehicles</span>
    </div>
  </div>

  <main class="page-main">
    <div class="container">

      <div class="page-header">
        <h1>Search Vehicles</h1>
        <p>Enter a model keyword or year to find matching used cars.</p>
      </div>

      <form id="search-form" method="GET" action="results.php" novalidate style="max-width: 640px;">
        <div class="search-row">

          <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="model">Model</label>
            <input class="form-input" type="text" id="model" name="model"
                   placeholder="e.g. Toyota, BMW, Tesla">
          </div>

          <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="year">Year</label>
            <input class="form-input" type="number" id="year" name="year"
                   placeholder="e.g. 2022" min="1990" max="2026">
          </div>

          <div>
            <div style="height: 19px;"></div>
            <button type="submit" class="btn btn-primary" style="height: 40px; white-space: nowrap;">
              Search
            </button>
          </div>

        </div>
        <p class="text-sm text-muted mt-8">
          Both fields are optional. Fill in one or both, or leave blank to browse all vehicles.
        </p>
      </form>

      <div style="border-top: 1px solid #E5E5E5; margin: 48px 0;"></div>

      <div>
        <p class="section-label mb-16">Popular Brands</p>
        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
          <a href="results.php?model=Toyota"        class="btn btn-secondary btn-sm">Toyota</a>
          <a href="results.php?model=Honda"         class="btn btn-secondary btn-sm">Honda</a>
          <a href="results.php?model=Tesla"         class="btn btn-secondary btn-sm">Tesla</a>
          <a href="results.php?model=BMW"           class="btn btn-secondary btn-sm">BMW</a>
          <a href="results.php?model=Volkswagen"    class="btn btn-secondary btn-sm">Volkswagen</a>
          <a href="results.php?model=Mercedes-Benz" class="btn btn-secondary btn-sm">Mercedes-Benz</a>
          <a href="results.php?model=Audi"          class="btn btn-secondary btn-sm">Audi</a>
          <a href="results.php"                     class="btn btn-secondary btn-sm">All Vehicles</a>
        </div>
      </div>

    </div>
  </main>

  <footer>
    <div class="footer-inner">
      <span class="footer-copy">© 2026 DriveEasy. All rights reserved.</span>
      <ul class="footer-links">
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
