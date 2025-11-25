-- ============================================
-- Sample Data for Plants Management System
-- ============================================

USE plants_management;

-- Insert sample admin user (password: admin123)
INSERT INTO users (email, password, username, role) VALUES
('admin@plants.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'admin');

-- Insert sample regular user (password: user123)
INSERT INTO users (email, password, username, bio, city) VALUES
('user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'PlantLover', 'I love indoor plants!', 'Paris');

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




