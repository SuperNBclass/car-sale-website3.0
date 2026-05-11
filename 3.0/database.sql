-- ============================================================
-- Online Car Sale — Database Schema
-- Course: QHE4103 Fundamentals of Web Technology
-- Phase B: Website Back-end Development
-- ============================================================

CREATE DATABASE IF NOT EXISTS carsale_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE carsale_db;

-- ── Sellers ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS sellers (
    id         INT           AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    address    VARCHAR(255)  NOT NULL,
    phone      VARCHAR(20)   NOT NULL,
    email      VARCHAR(100)  NOT NULL UNIQUE,
    username   VARCHAR(50)   NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ── Cars ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cars (
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
);

-- ── Demo data (password: demo123) ───────────────────────────
INSERT INTO sellers (name, address, phone, email, username, password) VALUES
('Demo User', '123 Demo Street Beijing', '13800138000', 'demo@example.com', 'demo',
 '$2y$10$bAcy8cOvlvJ.GF396UBpNeJt70.wMvPduTwar1fCwDiqn2CZV0OmW');

INSERT INTO cars (seller_id, model, year, color, price, location, image_path, created_at) VALUES
(1, 'Toyota Camry',         2022, 'Pearl White',    185000, 'Beijing',   'images/toyota-camry-2022.jpg',      '2024-03-01'),
(1, 'Honda Civic',          2021, 'Midnight Black',  142000, 'Shanghai',  'images/honda-civic-2021.jpg',       '2024-02-15'),
(1, 'Tesla Model 3',        2023, 'Solid White',     268000, 'Shenzhen',  'images/tesla-model-3-2023.jpg',     '2024-03-10'),
(1, 'BMW 3 Series',         2020, 'Space Gray',      248000, 'Guangzhou', 'images/bmw-3-series-2020.jpg',      '2024-01-20'),
(1, 'Volkswagen Passat',    2019, 'Reflex Silver',   138000, 'Chengdu',   'images/volkswagen-passat-2019.png', '2024-01-05'),
(1, 'Toyota Corolla',       2018, 'Celestial Blue',   98000, 'Hangzhou',  'images/toyota-corolla-2018.jpg',    '2024-02-01'),
(1, 'Mercedes-Benz C-Class',2022, 'Obsidian Black',  328000, 'Beijing',   'images/mercedes-c-class-2022.png', '2024-03-15'),
(1, 'Audi A4',              2021, 'Ibis White',      298000, 'Shanghai',  'images/audi-a4-2021.jpg',           '2024-02-28');
