# Commit Message

```
feat: Add admin dashboard, filter system, plant categories, and improve plant detail page

## Major Features Added:

### 1. Admin Dashboard
- Add adminDashboard() method to DashboardController with comprehensive admin statistics
- Create admin dashboard view with:
  - All user dashboard features (statistics, charts, plants needing care)
  - Admin-specific statistics (total users, new users, posts, orders, revenue, etc.)
  - User activity charts (last 30 days)
  - Posts activity charts
  - Orders by status chart
  - System health monitoring (database, disk space, PHP version)
  - Recent users and orders lists
- Add floating menu for "Tableau de bord" with options:
  - "Tableau de bord" (regular dashboard) for all users
  - "Tableau administratif" (admin dashboard) for admin users only

### 2. Filter Sidebar and Sort System
- Add comprehensive filter sidebar to catalog and marketplace pages:
  - Collapsible filter sections with smooth animations
  - Search functionality
  - Multiple filter options (difficulty, light, water, humidity, categories)
  - "Clear filters" button
- Add sort dropdown in top right:
  - Catalog: Featured, Name A-Z/Z-A, Most Recent/Oldest
  - Marketplace: Featured, Price Low-High/High-Low, Name A-Z/Z-A, Most Recent
- Update PlantCatalog and Product models to support sorting
- Update controllers to handle sorting and additional filters
- Add responsive design for mobile devices

### 3. Plant Categories System
- Create database tables:
  - `categories` table for storing plant categories
  - `plant_category_assignments` table (many-to-many relationship)
- Add default categories:
  - Self-Watering Plants
  - Pet-Friendly Plants
  - Air Purifying Plants
  - Cold Weather Plants
  - Giant Plants
  - Low-Maintenance Plants
- Update PlantCatalog model:
  - Add category filtering support (multiple categories)
  - Add methods: getAllCategories(), getPlantCategories(), assignCategories(), getCategoryBySlug()
  - Support category filtering by slug (for URL links) and by IDs (for form)
- Update PlantCatalogController to handle category filters
- Add category filter section in catalog sidebar with checkboxes (multiple selection)
- Update floating menu category links to use category parameter
- Fix SQL queries in count() method to properly handle category joins

### 4. Admin Category Management
- Add category assignment interface in admin plants page:
  - Category checkboxes in create plant form
  - Category checkboxes in edit plant form (with current categories pre-selected)
  - Display category badges for each plant in the list
- Update AdminController to handle category assignments when creating/updating plants
- Add CSS styling for category checkboxes and badges

### 5. Plant Detail Page Improvements
- Add collapsible sections for better UX:
  - Categories section (always shown, collapsible)
  - Details section (collapsible)
  - Care section (collapsible)
  - Seed Guide section (if available, collapsible)
  - Mature Plant Guide section (if available, collapsible)
- Description always visible at top (no scrolling needed)
- Add category display in detail page:
  - Pet-Friendly status: "No toxic" or "Not PF" with color coding
  - All other categories with descriptions (e.g., "Yes—Releases oxygen and absorbs pollutants")
  - Green icons for all section headers (consistent with Details and Care)
- Add smooth expand/collapse animations
- Improve visual hierarchy and readability

## Bug Fixes:
- Fix SQL error in count() method when searching plants (column alias issue)
- Fix SQL error in count() method when filtering by category (JOIN clause alias issue)
- Fix active users query to properly join with user_plants table

## Database Changes:
- Add `database/add_plant_categories.sql` - Creates categories system tables
- Add `database/update_category_descriptions.sql` - Updates category descriptions with better formatting

## Files Modified:
- app/controllers/DashboardController.php - Add adminDashboard method
- app/controllers/PlantCatalogController.php - Add category filtering and sorting
- app/controllers/MarketplaceController.php - Add sorting support
- app/controllers/AdminController.php - Add category assignment handling
- app/models/PlantCatalog.php - Add category methods and fix SQL queries
- app/models/Product.php - Add sorting support
- app/views/dashboard/admin.php - New admin dashboard view
- app/views/plant_catalog/index.php - Add filter sidebar and sort dropdown
- app/views/plant_catalog/detail.php - Add collapsible sections and category display
- app/views/marketplace/index.php - Add filter sidebar and sort dropdown
- app/views/admin/plants.php - Add category assignment interface
- app/views/layouts/header.php - Add dashboard floating menu
- public/assets/css/style.css - Add styles for filters, categories, collapsible sections
- database/add_plant_categories.sql - New file for categories system
- database/update_category_descriptions.sql - New file for updating descriptions

## Technical Details:
- All SQL queries use proper table aliases to avoid column not found errors
- Category filtering supports both single category (by slug) and multiple categories (by IDs)
- Filter sidebar is sticky on desktop, responsive on mobile
- All collapsible sections remember their state and open automatically if they have selected values
- Category descriptions formatted with "Yes—" prefix for better readability
```
