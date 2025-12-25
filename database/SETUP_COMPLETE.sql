-- ============================================
-- COMPLETE DATABASE SETUP FOR PLANTS MANAGEMENT SYSTEM
-- ============================================
-- Run this file in phpMyAdmin or MySQL command line
-- This includes: Database creation, all tables, and sample data
-- ============================================

-- Step 1: Create Database
CREATE DATABASE IF NOT EXISTS plants_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE plants_management;

-- ============================================
-- USERS TABLE
-- ============================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(100) NOT NULL,
    bio TEXT,
    avatar_url VARCHAR(500),
    role ENUM('user', 'admin') DEFAULT 'user',
    city VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PLANT CATALOG TABLE
-- ============================================
CREATE TABLE plant_catalog (
    id INT PRIMARY KEY AUTO_INCREMENT,
    common_name VARCHAR(255) NOT NULL,
    scientific_name VARCHAR(255),
    description TEXT,
    difficulty_level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
    light_requirement ENUM('low', 'medium', 'high') DEFAULT 'medium',
    water_requirement ENUM('low', 'medium', 'high') DEFAULT 'medium',
    humidity_preference ENUM('low', 'medium', 'high') DEFAULT 'medium',
    temperature_min INT,
    temperature_max INT,
    image_url VARCHAR(500),
    recommended_for_beginners BOOLEAN DEFAULT FALSE,
    default_watering_interval_days INT DEFAULT 7,
    default_fertilizing_interval_days INT DEFAULT 30,
    seed_guide TEXT,
    mature_plant_guide TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_difficulty (difficulty_level),
    INDEX idx_light (light_requirement),
    INDEX idx_common_name (common_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- USER PLANTS TABLE (Personal Collection)
-- ============================================
CREATE TABLE user_plants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    plant_catalog_id INT NOT NULL,
    nickname_for_plant VARCHAR(255),
    is_from_marketplace BOOLEAN DEFAULT FALSE,
    purchase_date DATE,
    acquisition_type ENUM('seed', 'plant', 'unknown') DEFAULT 'unknown',
    last_watering_date DATE,
    last_fertilizing_date DATE,
    custom_watering_interval_days INT,
    custom_fertilizing_interval_days INT,
    room_location VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plant_catalog_id) REFERENCES plant_catalog(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_plant_catalog_id (plant_catalog_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PLANT CARE EVENTS TABLE
-- ============================================
CREATE TABLE plant_care_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_plant_id INT NOT NULL,
    event_type ENUM('watering', 'fertilizing', 'repotting', 'other') NOT NULL,
    event_date DATE NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_plant_id) REFERENCES user_plants(id) ON DELETE CASCADE,
    INDEX idx_user_plant_id (user_plant_id),
    INDEX idx_event_date (event_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PLANT PHOTOS TABLE
-- ============================================
CREATE TABLE plant_photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_plant_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    caption TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_plant_id) REFERENCES user_plants(id) ON DELETE CASCADE,
    INDEX idx_user_plant_id (user_plant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PRODUCTS TABLE (Marketplace)
-- ============================================
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category ENUM('seed', 'plant', 'pot', 'soil', 'fertilizer', 'accessory') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(500),
    stock INT DEFAULT 0,
    is_seed BOOLEAN DEFAULT FALSE,
    related_plant_catalog_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (related_plant_catalog_id) REFERENCES plant_catalog(id) ON DELETE SET NULL,
    INDEX idx_category (category),
    INDEX idx_related_plant (related_plant_catalog_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ORDERS TABLE
-- ============================================
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ORDER ITEMS TABLE
-- ============================================
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- POSTS TABLE (Social Feed)
-- ============================================
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    content_text TEXT NOT NULL,
    image_url VARCHAR(500),
    related_user_plant_id INT,
    post_type ENUM('normal', 'help', 'article') DEFAULT 'normal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (related_user_plant_id) REFERENCES user_plants(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_post_type (post_type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- POST LIKES TABLE
-- ============================================
CREATE TABLE post_likes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (post_id, user_id),
    INDEX idx_post_id (post_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- COMMENTS TABLE
-- ============================================
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_post_id (post_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- NOTIFICATIONS TABLE
-- ============================================
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    link_url VARCHAR(500),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CART ITEMS TABLE (Persistent Shopping Cart)
-- ============================================
CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_user_id (user_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- FOLLOWS TABLE (Optional - for following users)
-- ============================================
CREATE TABLE IF NOT EXISTS follows (
    id INT PRIMARY KEY AUTO_INCREMENT,
    follower_id INT NOT NULL,
    following_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_follow (follower_id, following_id),
    INDEX idx_follower_id (follower_id),
    INDEX idx_following_id (following_id),
    CHECK (follower_id != following_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PRODUCT IMAGES TABLE (Optional - for multiple images per product)
-- ============================================
CREATE TABLE IF NOT EXISTS product_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SAMPLE DATA
-- ============================================

-- Insert sample admin user (password: admin123)
INSERT INTO users (email, password, username, role) VALUES
('admin@plants.com', '$2y$10$mH4aCE6pothThWOHge.O4.Rg6jZVMGT/FmKvHAE8FO2X2LqKi8Egq', 'Admin', 'admin');

-- Insert sample regular user (password: user123)
INSERT INTO users (email, password, username, bio, city) VALUES
('user@example.com', '$2y$10$KkWWTVyoiaZcM7v8C4aMUeMmwZfowMjfn5WIrjpp3wudlqlC5W63y', 'PlantLover', 'I love indoor plants!', 'Paris');

-- Insert sample plants in catalog
INSERT INTO plant_catalog (common_name, scientific_name, description, difficulty_level, light_requirement, water_requirement, humidity_preference, temperature_min, temperature_max, image_url, recommended_for_beginners, default_watering_interval_days, default_fertilizing_interval_days, seed_guide, mature_plant_guide) VALUES
('Monstera Deliciosa', 'Monstera deliciosa', 'A popular tropical plant with large, glossy leaves that develop characteristic splits and holes as they mature. Perfect for adding a jungle vibe to any room.', 'beginner', 'medium', 'medium', 'medium', 18, 27, 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?w=400', TRUE, 7, 30, 
'Plant seeds in well-draining soil, keep moist but not waterlogged. Maintain temperature 20-25°C. Germination takes 2-4 weeks. Provide indirect bright light.',
'Water when top 2-3cm of soil is dry. Prefers bright, indirect light. Mist leaves occasionally. Fertilize monthly during growing season. Repot every 2-3 years.'),

('Snake Plant', 'Sansevieria trifasciata', 'An extremely hardy plant that can survive in low light and with minimal water. Perfect for beginners and busy plant parents.', 'beginner', 'low', 'low', 'low', 15, 30, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', TRUE, 14, 60,
'Plant seeds in sandy, well-draining soil. Keep slightly moist. Germination is slow, 4-8 weeks. Low light is acceptable.',
'Water sparingly, only when soil is completely dry. Can tolerate low light but prefers bright indirect light. Very drought tolerant. Fertilize 2-3 times per year.'),

('Pothos', 'Epipremnum aureum', 'A fast-growing trailing plant that is almost impossible to kill. Great for hanging baskets or training up a trellis.', 'beginner', 'medium', 'medium', 'medium', 18, 26, 'https://images.unsplash.com/photo-1519336056116-9e799c0a78b9?w=400', TRUE, 7, 30,
'Rarely grown from seed. Propagate from cuttings instead. Place cutting in water or soil, roots will develop in 2-3 weeks.',
'Water when top inch of soil is dry. Prefers bright indirect light but tolerates low light. Pinch back to encourage bushiness. Fertilize monthly in growing season.'),

('Fiddle Leaf Fig', 'Ficus lyrata', 'A stunning plant with large, violin-shaped leaves. Requires more attention but rewards with dramatic beauty.', 'intermediate', 'high', 'medium', 'high', 18, 24, 'https://images.unsplash.com/photo-1463320898486-8c50c4e2a0a3?w=400', FALSE, 7, 30,
'Not typically grown from seed. Purchase as young plant and provide consistent care.',
'Water when top 2-3cm of soil is dry. Needs bright, indirect light. Keep away from drafts. Mist regularly or use humidifier. Rotate weekly for even growth. Fertilize monthly.'),

('ZZ Plant', 'Zamioculcas zamiifolia', 'An incredibly low-maintenance plant with glossy, dark green leaves. Thrives on neglect.', 'beginner', 'low', 'low', 'low', 15, 26, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', TRUE, 21, 90,
'Propagate by division of rhizomes. Plant in well-draining soil. Keep slightly moist initially.',
'Water only when soil is completely dry (every 2-3 weeks). Tolerates low light but grows faster in bright indirect light. Very drought tolerant. Fertilize 2-3 times per year.'),

('Peace Lily', 'Spathiphyllum', 'Elegant plant with white flowers and dark green leaves. Known for air-purifying qualities.', 'beginner', 'low', 'high', 'high', 18, 24, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', TRUE, 5, 30,
'Rarely grown from seed. Divide mature plants or purchase as young plant.',
'Keep soil consistently moist but not waterlogged. Prefers low to medium light. Drooping leaves indicate need for water. Mist regularly. Fertilize monthly during growing season.'),

('Spider Plant', 'Chlorophytum comosum', 'Easy-to-grow plant that produces baby plantlets. Perfect for hanging baskets.', 'beginner', 'medium', 'medium', 'medium', 15, 26, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', TRUE, 7, 30,
'Plant seeds in well-draining soil. Keep moist. Germination in 2-3 weeks. Or propagate from plantlets.',
'Water when top inch of soil is dry. Prefers bright indirect light. Produces plantlets that can be propagated. Fertilize monthly during growing season.'),

('Rubber Plant', 'Ficus elastica', 'A bold plant with large, glossy leaves. Adds a statement to any room.', 'intermediate', 'medium', 'medium', 'medium', 18, 24, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', FALSE, 7, 30,
'Not typically grown from seed. Purchase as young plant.',
'Water when top 2-3cm of soil is dry. Prefers bright indirect light. Wipe leaves occasionally to keep them shiny. Fertilize monthly during growing season.'),

('Alocasia', 'Alocasia amazonica', 'Dramatic plant with arrow-shaped leaves and striking veins. Requires high humidity.', 'advanced', 'medium', 'high', 'high', 20, 25, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', FALSE, 5, 30,
'Plant tubers in well-draining soil. Keep warm and humid. Germination can be slow.',
'Keep soil consistently moist. Needs high humidity (60%+). Prefers bright indirect light. Mist regularly or use pebble tray. Fertilize every 2 weeks during growing season.'),

('Philodendron', 'Philodendron hederaceum', 'Classic trailing plant that is easy to care for and grows quickly.', 'beginner', 'medium', 'medium', 'medium', 18, 26, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', TRUE, 7, 30,
'Rarely grown from seed. Propagate from stem cuttings in water or soil.',
'Water when top inch of soil is dry. Prefers bright indirect light. Pinch back to encourage bushiness. Fertilize monthly during growing season.');

-- Insert sample products
INSERT INTO products (name, description, category, price, image_url, stock, is_seed, related_plant_catalog_id) VALUES
('Monstera Deliciosa - Mature Plant', 'Beautiful mature Monstera plant, 30-40cm tall', 'plant', 29.99, 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?w=400', 15, FALSE, 1),
('Monstera Deliciosa Seeds', 'Premium Monstera seeds, pack of 5', 'seed', 9.99, 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?w=400', 50, TRUE, 1),
('Snake Plant - Mature', 'Hardy Snake Plant, 25-30cm', 'plant', 19.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 20, FALSE, 2),
('Pothos Golden - Cuttings', 'Rooted Pothos cuttings, ready to plant', 'plant', 12.99, 'https://images.unsplash.com/photo-1519336056116-9e799c0a78b9?w=400', 30, FALSE, 3),
('Ceramic Pot - White', 'Beautiful white ceramic pot, 15cm diameter', 'pot', 14.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 40, FALSE, NULL),
('Premium Potting Soil', 'Well-draining potting mix for indoor plants, 5L', 'soil', 8.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 100, FALSE, NULL),
('Liquid Fertilizer', 'Balanced liquid fertilizer for houseplants, 500ml', 'fertilizer', 12.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 60, FALSE, NULL),
('Plant Mister', 'Fine mist sprayer for increasing humidity', 'accessory', 6.99, 'https://images.unsplash.com/photo-1593691501777-1c0e0a1a3a1a?w=400', 50, FALSE, NULL);

-- ============================================
-- SETUP COMPLETE!
-- ============================================
-- Default login credentials:
-- Admin: admin@plants.com / admin123
-- User: user@example.com / user123
-- ============================================

