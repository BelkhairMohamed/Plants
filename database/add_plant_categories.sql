-- ============================================
-- Plant Categories System
-- ============================================
-- This script adds category support for plants
-- Run this after the main database setup

USE plants_management;

-- ============================================
-- CATEGORIES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PLANT CATEGORY ASSIGNMENTS TABLE (Many-to-Many)
-- ============================================
CREATE TABLE IF NOT EXISTS plant_category_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    plant_catalog_id INT NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (plant_catalog_id) REFERENCES plant_catalog(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_assignment (plant_catalog_id, category_id),
    INDEX idx_plant_catalog_id (plant_catalog_id),
    INDEX idx_category_id (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT DEFAULT CATEGORIES
-- ============================================
INSERT INTO categories (name, slug, description) VALUES
('Self-Watering Plants', 'self_watering', 'Yes—Can survive with minimal watering or have self-watering systems'),
('Pet-Friendly Plants', 'pet_friendly', 'Non-toxic plants safe for pets'),
('Air Purifying Plants', 'air_purifying', 'Yes—Releases oxygen and absorbs pollutants'),
('Cold Weather Plants', 'cold_weather', 'Yes—Can tolerate lower temperatures'),
('Giant Plants', 'giant_plants', 'Yes—Large plants perfect for statement pieces'),
('Low-Maintenance Plants', 'low_maintenance', 'Yes—Easy-care plants perfect for beginners')
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description);

