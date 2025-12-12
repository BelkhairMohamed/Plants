# How to Add Multiple Images to Products

## Quick Check: Which Products Have Images?

Run this SQL to see all products with their image counts:

```sql
SELECT 
    p.id AS product_id,
    p.name AS product_name,
    COUNT(pi.id) AS image_count
FROM products p
LEFT JOIN product_images pi ON p.id = pi.product_id
GROUP BY p.id, p.name
ORDER BY p.id;
```

**To view a product with images:**
1. Note the `product_id` from the query above
2. Visit: `/?controller=marketplace&action=detail&id=PRODUCT_ID`
3. You'll see the carousel if there are 2+ images

## Step 1: Run the Migration

First, you need to create the `product_images` table by running the migration:

**Option A: Using MySQL command line:**
```bash
mysql -u your_username -p plants_management < database/migration_add_product_images_table.sql
```

**Option B: Using phpMyAdmin:**
1. Open phpMyAdmin
2. Select your `plants_management` database
3. Go to the "SQL" tab
4. Copy and paste the contents of `database/migration_add_product_images_table.sql`
5. Click "Go"

**Option C: Using MySQL Workbench:**
1. Open the SQL file `database/migration_add_product_images_table.sql`
2. Execute it in your database

## Step 2: Add Images to Products

### Method 1: Using SQL (Recommended)

```sql
-- Example: Add 3 images to product ID 1
-- Replace the URLs with your actual image URLs
INSERT INTO product_images (product_id, image_url, display_order) VALUES
(1, 'https://example.com/products/plant-front.jpg', 0),
(1, 'https://example.com/products/plant-side.jpg', 1),
(1, 'https://example.com/products/plant-detail.jpg', 2);
```

**To find your product ID:**
```sql
SELECT id, name FROM products;
```

### Method 2: Using PHP Code (In a script or admin panel)

```php
require_once 'config/autoload.php';

$productModel = new Product();

// Add images to product ID 1
$productModel->addImage(1, 'https://example.com/image1.jpg', 0);
$productModel->addImage(1, 'https://example.com/image2.jpg', 1);
$productModel->addImage(1, 'https://example.com/image3.jpg', 2);
```

### Method 3: Quick SQL Example

```sql
-- Add images to multiple products at once
INSERT INTO product_images (product_id, image_url, display_order) VALUES
(1, 'https://via.placeholder.com/500?text=Image+1', 0),
(1, 'https://via.placeholder.com/500?text=Image+2', 1),
(1, 'https://via.placeholder.com/500?text=Image+3', 2),
(2, 'https://via.placeholder.com/500?text=Product+2+Image+1', 0),
(2, 'https://via.placeholder.com/500?text=Product+2+Image+2', 1);
```

## How It Works

- The `product_images` table stores multiple images per product
- Each image has a `display_order` to control the sequence (0 = first, 1 = second, etc.)
- The carousel will automatically show all images for a product
- If no images are in `product_images`, it falls back to the `image_url` field in the products table
- The first image (display_order = 0) is shown by default
- Images are displayed in order by `display_order` (ascending), then by `id`

## Viewing Images

Once you've added images, visit the product detail page:
```
/?controller=marketplace&action=detail&id=PRODUCT_ID
```

You'll see:
- Arrow buttons (left/right) to navigate between images
- Dot indicators at the bottom to jump to any image
- Smooth transitions between images

## Managing Images

### View all images for a product:
```sql
SELECT * FROM product_images WHERE product_id = 1 ORDER BY display_order;
```

### Delete a specific image:
```sql
DELETE FROM product_images WHERE id = IMAGE_ID;
```

### Delete all images for a product:
```sql
DELETE FROM product_images WHERE product_id = PRODUCT_ID;
```

### Update image order:
```sql
UPDATE product_images SET display_order = 2 WHERE id = IMAGE_ID;
```

## Notes

- The carousel arrows and indicators only appear when there are 2+ images
- Single images still work (no carousel, just the image)
- The old `image_url` field in products table is kept for backward compatibility
- If a product has images in `product_images`, those are used. Otherwise, it falls back to `image_url`

