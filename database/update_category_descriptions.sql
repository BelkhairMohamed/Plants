-- ============================================
-- Update Category Descriptions
-- ============================================
-- Run this to update existing category descriptions
-- with better formatted descriptions

USE plants_management;

UPDATE categories 
SET description = 'Yes—Can survive with minimal watering or have self-watering systems'
WHERE slug = 'self_watering';

UPDATE categories 
SET description = 'Yes—Releases oxygen and absorbs pollutants'
WHERE slug = 'air_purifying';

UPDATE categories 
SET description = 'Yes—Can tolerate lower temperatures'
WHERE slug = 'cold_weather';

UPDATE categories 
SET description = 'Yes—Large plants perfect for statement pieces'
WHERE slug = 'giant_plants';

UPDATE categories 
SET description = 'Yes—Easy-care plants perfect for beginners'
WHERE slug = 'low_maintenance';

