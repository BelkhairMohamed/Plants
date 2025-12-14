# Database Setup Guide

## Quick Setup

For a fresh installation, use the complete setup script:

```sql
-- Run this in phpMyAdmin or MySQL command line
source database/SETUP_COMPLETE.sql;
```

Or execute it directly:
1. Open phpMyAdmin
2. Select "Import"
3. Choose `SETUP_COMPLETE.sql`
4. Click "Go"

This will create:
- Database `plants_management`
- All tables with proper structure
- Sample data (admin user, test user, plants, products)

## Default Login Credentials

After running `SETUP_COMPLETE.sql`:

**Admin:**
- Email: `admin@plants.com`
- Password: `admin123`

**User:**
- Email: `user@example.com`
- Password: `user123`

## Manual Setup (Alternative)

If you prefer step-by-step:

1. **Create database and tables:**
   ```sql
   source database/schema.sql;
   ```

2. **Add sample data:**
   ```sql
   source database/seed_data.sql;
   ```

3. **Add additional plants (optional):**
   ```sql
   source database/add_philodendron_varieties.sql;
   ```

## 📸 Handling Images When Sharing Data

### Understanding Image Storage

Images in your database are stored as **URLs**, not as files. There are two types:

1. **External URLs** (e.g., `https://images.unsplash.com/...`, `https://example.com/image.jpg`)
   - ✅ Work everywhere - no files to copy
   - ✅ Best choice for sharing between machines
   - ✅ **If your images are from other websites, you're all set!**

2. **Local URLs** (e.g., `http://localhost/Plants-main/public/Images/photo.jpg`)
   - ⚠️ Files must exist on each machine
   - ⚠️ Need to copy files separately

### What Happens to Images When Exporting Database?

When you export your database, **only the URLs are exported**, not the actual image files.

**If your images are:**
- **External URLs** (from other websites) → ✅ **They'll work on the new machine automatically - no action needed!**
- Local file paths → ⚠️ You need to copy the image files too

### Steps for Sharing with Images

**If your images are external URLs (from other websites):**
- ✅ **No special action needed!**
- Just export and import the database
- Images will work automatically on the new machine

**If you have local image files (rare case):**

1. **Check what type of images you have:**
   ```sql
   -- In phpMyAdmin, run this query to find local URLs:
   SELECT image_url FROM plant_catalog WHERE image_url LIKE 'http://localhost%';
   SELECT image_url FROM products WHERE image_url LIKE 'http://localhost%';
   SELECT avatar_url FROM users WHERE avatar_url LIKE 'http://localhost%';
   ```

2. **If you have local image files:**
   - Copy the entire `public/Images/` folder (if you added custom images)
   - Copy the entire `public/Video/` folder (if you added custom videos)
   - Or copy specific files that are referenced in your database

3. **Export database** (as described below)

**On the new machine:**

1. **Import database** (as described below)

2. **Copy image files:**
   - Paste `public/Images/` folder to the new machine
   - Paste `public/Video/` folder to the new machine
   - Ensure folder structure matches exactly

3. **Update BASE_URL if needed:**
   - If your new machine has a different URL, update `config/constants.php`
   - Or update image URLs in database to match new `BASE_URL`

### Recommendation

**✅ If your images are already external URLs (from other websites):**
- You're already set up perfectly!
- No files to copy
- Just export/import the database and everything will work

**If you currently have local files and want easier sharing:**
- Upload images to Imgur, Cloudinary, or similar service
- Or use Unsplash/other free image services
- Update URLs in database to use external links
- This way, no files to copy!

## Sharing Database Data Between Machines

### ❌ DO NOT:
- **Never commit database dumps to GitHub**
  - Security risk (passwords, personal data)
  - Large file sizes
  - Sensitive user information
  - Violates best practices

### ✅ DO:

#### Option 1: Use Seed Data (Recommended for Development)
The `seed_data.sql` file contains sample data for testing. This is already in the repository and safe to use.

#### Option 2: Export/Import Separately (For Your Actual Data)

**On your current machine:**

1. **Export your database:**
   ```bash
   # Using mysqldump
   mysqldump -u root -p plants_management > my_database_backup.sql
   ```

   Or in phpMyAdmin:
   - Select database `plants_management`
   - Click "Export" tab
   - Choose "Quick" or "Custom"
   - Click "Go" and save the file

2. **Handle Images:**
   
   **Important:** Images in the database are stored as URLs. You need to check:
   
   - **External URLs** (e.g., `https://images.unsplash.com/...`):
     - ✅ No action needed - they work everywhere
   
   - **Local file paths** (e.g., `http://localhost/Plants-main/public/Images/...`):
     - ✅ If files are in `public/Images/` or `public/Video/` and already in Git → No action needed
     - ⚠️ If you uploaded files via admin panel to a custom location:
       - Copy those image files to the new machine
       - Or update URLs in database to use external URLs
   
   **To copy local image files:**
   ```bash
   # Copy the entire public folder (if you have custom uploads)
   # Or just copy specific directories:
   # - public/Images/ (if you added custom images)
   # - public/Video/ (if you added custom videos)
   ```

3. **Share the files privately:**
   - Database backup: USB drive, cloud storage (Google Drive, Dropbox), or email
   - Image files: Same method
   - **DO NOT commit to Git**

4. **On the new machine:**

   a. **Import the database:**
   ```bash
   # Import the database
   mysql -u root -p plants_management < my_database_backup.sql
   ```

   Or in phpMyAdmin:
   - Create database `plants_management` first
   - Select the database
   - Click "Import" tab
   - Choose your backup file
   - Click "Go"

   b. **Copy image files (only if you have local files):**
   - If you have custom image files with local paths, copy them to the same location on the new machine
   - Ensure the folder structure matches: `public/Images/`, `public/Video/`, etc.
   - **If all your images are external URLs, skip this step!**

   c. **Update image URLs if needed (only for local files):**
   - If your `BASE_URL` changed and you have local image paths, update URLs in the database
   - Or convert local URLs to external URLs for better portability

#### Option 3: Create a Development Seed File

If you want to share specific test data:

1. Create a new file: `database/my_test_data.sql`
2. Export only the data you want to share (not user passwords)
3. Add it to `.gitignore` if it contains sensitive data
4. Or sanitize it (remove real emails, hash passwords, etc.)

Example:
```sql
-- database/my_test_data.sql
-- Only non-sensitive test data

INSERT INTO plant_catalog (...) VALUES (...);
INSERT INTO products (...) VALUES (...);
-- NO user data with real passwords
```

## Handling Images

### Types of Images in Database

1. **External URLs** (Recommended):
   - Examples: `https://images.unsplash.com/...`, `https://example.com/image.jpg`
   - ✅ Work on any machine
   - ✅ No files to copy
   - ✅ Best for sharing between machines

2. **Local URLs** (Current setup):
   - Examples: `http://localhost/Plants-main/public/Images/photo.jpg`
   - ⚠️ Files must exist on each machine
   - ⚠️ Need to copy files separately
   - ⚠️ URLs may break if `BASE_URL` changes

### Image Storage Locations

- `public/Images/` - Static images (profile icons, etc.)
- `public/Video/` - Video files (hero background, etc.)
- Database URLs - Stored in tables:
  - `plant_catalog.image_url`
  - `plant_catalog_images.image_url`
  - `products.image_url`
  - `users.avatar_url`
  - `posts.image_url`

### Best Practice for Images

**For maximum portability:**
1. Use external URLs (Unsplash, Imgur, Cloudinary, etc.)
2. Or use relative paths that work with `BASE_URL`
3. Keep static assets (like profile icons) in Git
4. Don't commit user-uploaded images to Git

## Best Practices

1. **Development/Testing:**
   - Use `seed_data.sql` for consistent test data
   - All developers use the same sample data
   - Use external image URLs for easy sharing

2. **Production:**
   - Never export production databases
   - Use proper backup systems
   - Keep backups secure and private
   - Use CDN or external storage for images

3. **Personal Data:**
   - Export separately when needed
   - Share via secure channels (not Git)
   - Consider sanitizing sensitive data
   - Copy image files separately if using local paths

## File Structure

```
database/
├── SETUP_COMPLETE.sql          # Complete setup (recommended)
├── schema.sql                  # Database structure only
├── seed_data.sql               # Sample data only
├── add_philodendron_varieties.sql  # Additional plants
└── README.md                   # This file
```

## Troubleshooting

### "Table already exists" error
- Drop the database first: `DROP DATABASE plants_management;`
- Then run `SETUP_COMPLETE.sql` again

### Import fails
- Check MySQL version compatibility
- Ensure UTF-8 encoding
- Check file size limits in phpMyAdmin

### Password hash issues
- Use `password_hash()` in PHP to generate new hashes
- Default passwords in seed_data.sql are already hashed

## Security Notes

- Database dumps contain sensitive information
- Never commit `.sql` backup files to Git
- Use `.gitignore` to prevent accidental commits
- Share database backups through secure channels only

