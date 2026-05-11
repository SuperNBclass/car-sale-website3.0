<?php
session_start();
require 'db.php';

function h($s)
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// Must be logged in
if (!isset($_SESSION['user'])) {
  header('Location: login.php?required=1');
  exit;
}
$user = $_SESSION['user'];

$errors = [];
$fields = ['model' => '', 'year' => '', 'color' => '', 'price' => '', 'location' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($fields as $key => $_) {
    $fields[$key] = trim($_POST[$key] ?? '');
  }

  // Validate required fields
  foreach (['model', 'year', 'color', 'price', 'location'] as $f) {
    if (!$fields[$f]) $errors[$f] = 'This field is required';
  }

  if (!isset($errors['year']) && $fields['year']) {
    $y = (int)$fields['year'];
    if ($y < 1990 || $y > 2026) $errors['year'] = 'Please enter a valid year (1990–2026)';
  }
  if (!isset($errors['price']) && $fields['price']) {
    if ((float)$fields['price'] <= 0) $errors['price'] = 'Price must be greater than 0';
  }

  // Handle image upload
  $imagePath = null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file     = $_FILES['image'];
    $allowed  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mimeType = mime_content_type($file['tmp_name']);

    if (!isset($allowed[$mimeType])) {
      $errors['image'] = 'Only JPG, PNG and WEBP images are allowed';
    } elseif ($file['size'] > 5 * 1024 * 1024) {
      $errors['image'] = 'Image must be under 5 MB';
    } else {
      $ext      = $allowed[$mimeType];
      $filename = uniqid('car_', true) . '.' . $ext;
      $dest     = __DIR__ . '/uploads/' . $filename;
      if (move_uploaded_file($file['tmp_name'], $dest)) {
        $imagePath = 'uploads/' . $filename;
      } else {
        $errors['image'] = 'Failed to save image. Please try again.';
      }
    }
  }

  if (empty($errors)) {
    $stmt = $pdo->prepare(
      'INSERT INTO cars (seller_id, model, year, color, price, location, image_path)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
      $user['id'],
      $fields['model'],
      (int)$fields['year'],
      $fields['color'],
      (float)$fields['price'],
      $fields['location'],
      $imagePath,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Vehicle listed successfully!'];
    header('Location: results.php');
    exit;
  }
}

// Consume flash
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Vehicle — DriveEasy</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body data-page="add-car">
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
          <span class="user-status">
            <strong><?= h($user['username']) ?></strong>
            <a href="logout.php" class="btn btn-secondary btn-sm">Sign Out</a>
          </span>
        </div>
      </div>
    </nav>

    <div class="sub-nav">
      <div class="sub-nav-inner">
        <span class="sub-nav-label">Seller Centre</span>
        <ul class="sub-nav-links">
          <li><a href="register.php">Register</a></li>
          <li><a href="login.php">Log In</a></li>
          <li><a href="add-car.php" class="active">Add Vehicle</a></li>
        </ul>
      </div>
    </div>

    <div class="breadcrumb">
      <div class="breadcrumb-inner">
        <a href="index.php">Home</a>
        <span class="breadcrumb-sep">/</span>
        <a href="seller.php">Seller Centre</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Add Vehicle</span>
      </div>
    </div>

    <main class="page-main">
      <div class="container">
        <div class="page-header">
          <h1>Add Vehicle</h1>
          <p>Listed by: <strong><?= h($user['username']) ?></strong></p>
        </div>

        <div class="form-wrap">

          <?php if ($flash): ?>
            <div class="flash flash-<?= h($flash['type']) ?>" role="alert"><?= h($flash['msg']) ?></div>
          <?php endif; ?>
          <?php if (!empty($errors)): ?>
            <div class="flash flash-error" role="alert">Please correct the errors below and try again.</div>
          <?php endif; ?>

          <form id="add-car-form" method="POST" action="add-car.php"
            enctype="multipart/form-data" novalidate>

            <div class="form-group">
              <label class="form-label" for="model">Model</label>
              <input class="form-input <?= isset($errors['model']) ? 'is-error' : '' ?>"
                type="text" id="model" name="model"
                placeholder="e.g. Toyota Camry"
                value="<?= h($fields['model']) ?>">
              <span class="form-error" id="model-error"><?= h($errors['model'] ?? '') ?></span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <div class="form-group">
                <label class="form-label" for="year">Year</label>
                <input class="form-input <?= isset($errors['year']) ? 'is-error' : '' ?>"
                  type="number" id="year" name="year"
                  placeholder="e.g. 2022" min="1990" max="2026"
                  value="<?= h($fields['year']) ?>">
                <span class="form-error" id="year-error"><?= h($errors['year'] ?? '') ?></span>
              </div>
              <div class="form-group">
                <label class="form-label" for="color">Colour</label>
                <input class="form-input <?= isset($errors['color']) ? 'is-error' : '' ?>"
                  type="text" id="color" name="color"
                  placeholder="e.g. Pearl White"
                  value="<?= h($fields['color']) ?>">
                <span class="form-error" id="color-error"><?= h($errors['color'] ?? '') ?></span>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="price">Price (CNY)</label>
              <input class="form-input <?= isset($errors['price']) ? 'is-error' : '' ?>"
                type="number" id="price" name="price"
                placeholder="e.g. 180000" min="1"
                value="<?= h($fields['price']) ?>">
              <span class="form-error" id="price-error"><?= h($errors['price'] ?? '') ?></span>
            </div>

            <div class="form-group">
              <label class="form-label" for="location">Location</label>
              <input class="form-input <?= isset($errors['location']) ? 'is-error' : '' ?>"
                type="text" id="location" name="location"
                placeholder="e.g. Beijing"
                value="<?= h($fields['location']) ?>">
              <span class="form-error" id="location-error"><?= h($errors['location'] ?? '') ?></span>
            </div>

            <div style="border-top: 1px solid #E5E5E5; margin: 28px 0;"></div>

            <div class="form-group">
              <label class="form-label" for="image">Vehicle Image</label>
              <input class="form-file" type="file" id="image" name="image" accept="image/*">
              <span class="form-hint">Supports JPG, PNG, WEBP. Max size 5 MB.</span>
              <span class="form-error" id="image-error"><?= h($errors['image'] ?? '') ?></span>
              <div class="img-preview" id="img-preview">
                <span class="ph">[Vehicle image preview]</span>
              </div>
            </div>

            <div class="mt-32">
              <button type="submit" class="btn btn-primary btn-lg btn-block">Publish Listing</button>
            </div>

            <p class="text-sm text-muted mt-16">
              Your listing will appear in search results immediately.
            </p>

          </form>
        </div>
      </div>
    </main>

    <footer>
      <div class="footer-inner">
        <span class="footer-copy">© Delivery 3. All rights reserved.</span>
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