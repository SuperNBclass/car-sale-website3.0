<?php
/**
 * setup.php — One-time database initialisation
 * Run this once: http://your-server/car-sale-website2.0/setup.php
 */

$host   = 'localhost';
$dbname = 'carsale_db';
$dbuser = 'root';
$dbpass = '';

$log = [];

try {
    // Connect without selecting a database first
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $dbuser, $dbpass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");
    $log[] = "✔ Database '$dbname' ready.";

    // sellers table
    $pdo->exec("CREATE TABLE IF NOT EXISTS sellers (
        id         INT           AUTO_INCREMENT PRIMARY KEY,
        name       VARCHAR(100)  NOT NULL,
        address    VARCHAR(255)  NOT NULL,
        phone      VARCHAR(20)   NOT NULL,
        email      VARCHAR(100)  NOT NULL UNIQUE,
        username   VARCHAR(50)   NOT NULL UNIQUE,
        password   VARCHAR(255)  NOT NULL,
        created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");
    $log[] = "✔ Table 'sellers' ready.";

    // cars table
    $pdo->exec("CREATE TABLE IF NOT EXISTS cars (
        id         INT            AUTO_INCREMENT PRIMARY KEY,
        seller_id  INT            NOT NULL,
        model      VARCHAR(100)   NOT NULL,
        year       SMALLINT       NOT NULL,
        color      VARCHAR(50)    NOT NULL,
        price      DECIMAL(12,2)  NOT NULL,
        location   VARCHAR(100)   NOT NULL,
        image_path VARCHAR(500)   DEFAULT NULL,
        created_at TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (seller_id) REFERENCES sellers(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    $log[] = "✔ Table 'cars' ready.";

    // Seed demo data only if tables are empty
    $count = $pdo->query("SELECT COUNT(*) FROM sellers")->fetchColumn();
    if ($count == 0) {
        $hash = password_hash('demo123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO sellers (name, address, phone, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Demo User', '123 Demo Street Beijing', '13800138000', 'demo@example.com', 'demo', $hash]);
        $sellerId = $pdo->lastInsertId();

        $cars = [
            ['Toyota Camry',          2022, 'Pearl White',    185000, 'Beijing',   'images/toyota-camry-2022.jpg',      '2024-03-01'],
            ['Honda Civic',           2021, 'Midnight Black', 142000, 'Shanghai',  'images/honda-civic-2021.jpg',       '2024-02-15'],
            ['Tesla Model 3',         2023, 'Solid White',    268000, 'Shenzhen',  'images/tesla-model-3-2023.jpg',     '2024-03-10'],
            ['BMW 3 Series',          2020, 'Space Gray',     248000, 'Guangzhou', 'images/bmw-3-series-2020.jpg',      '2024-01-20'],
            ['Volkswagen Passat',     2019, 'Reflex Silver',  138000, 'Chengdu',   'images/volkswagen-passat-2019.png', '2024-01-05'],
            ['Toyota Corolla',        2018, 'Celestial Blue',  98000, 'Hangzhou',  'images/toyota-corolla-2018.jpg',    '2024-02-01'],
            ['Mercedes-Benz C-Class', 2022, 'Obsidian Black', 328000, 'Beijing',   'images/mercedes-c-class-2022.png', '2024-03-15'],
            ['Audi A4',               2021, 'Ibis White',     298000, 'Shanghai',  'images/audi-a4-2021.jpg',           '2024-02-28'],
        ];

        $stmt = $pdo->prepare("INSERT INTO cars (seller_id, model, year, color, price, location, image_path, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($cars as $c) {
            $stmt->execute(array_merge([$sellerId], $c));
        }
        $log[] = "✔ Demo data seeded (seller: demo / demo123, 8 cars).";
    } else {
        $log[] = "ℹ Demo data skipped — sellers table already has data.";
    }

    // Create uploads directory
    $uploadsDir = __DIR__ . '/uploads';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
        $log[] = "✔ Created uploads/ directory.";
    } else {
        $log[] = "ℹ uploads/ directory already exists.";
    }

    $log[] = '<strong style="color:green">Setup complete! You can now <a href="index.php">visit the site</a>.</strong>';

} catch (PDOException $e) {
    $log[] = '<strong style="color:red">Error: ' . htmlspecialchars($e->getMessage()) . '</strong>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Setup — Car Sale</title>
  <style>body{font-family:monospace;padding:40px;} li{margin:6px 0;}</style>
</head>
<body>
  <h2>Database Setup</h2>
  <ul>
    <?php foreach ($log as $line): ?>
      <li><?= $line ?></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
