-- ============================================
-- Check Which Products Have Images
-- ============================================

-- View all products with their images
SELECT 
    p.id AS product_id,
    p.name AS product_name,
    p.category,
    COUNT(pi.id) AS image_count,
    GROUP_CONCAT(pi.image_url ORDER BY pi.display_order SEPARATOR ', ') AS image_urls
FROM products p
LEFT JOIN product_images pi ON p.id = pi.product_id
GROUP BY p.id, p.name, p.category
ORDER BY p.id;

-- View products that have images in product_images table
SELECT 
    p.id AS product_id,
    p.name AS product_name,
    pi.id AS image_id,
    pi.image_url,
    pi.display_order
FROM products p
INNER JOIN product_images pi ON p.id = pi.product_id
ORDER BY p.id, pi.display_order;

-- View products with multiple images (2+)
SELECT 
    p.id AS product_id,
    p.name AS product_name,
    COUNT(pi.id) AS image_count
FROM products p
INNER JOIN product_images pi ON p.id = pi.product_id
GROUP BY p.id, p.name
HAVING COUNT(pi.id) >= 2
ORDER BY image_count DESC;

-- Check a specific product (replace 1 with your product ID)
SELECT 
    p.id,
    p.name,
    p.image_url AS old_image_url,
    COUNT(pi.id) AS new_image_count
FROM products p
LEFT JOIN product_images pi ON p.id = pi.product_id
WHERE p.id = 1
GROUP BY p.id, p.name, p.image_url;

-- View all images for a specific product (replace 1 with your product ID)
SELECT 
    id,
    image_url,
    display_order,
    created_at
FROM product_images
WHERE product_id = 1
ORDER BY display_order, id;

