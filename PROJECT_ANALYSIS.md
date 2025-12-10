# 🌱 Plants Management System - Deep Analysis

## 📋 Project Overview

A comprehensive PHP-based web application for managing indoor plants with features including:
- Plant catalog with detailed care information
- Personal plant collection management
- Marketplace for purchasing plants and accessories
- Social community features (posts, likes, comments)
- Notification system
- Weather-based care recommendations
- Admin panel for content management

**Technology Stack:**
- Backend: PHP 8 (Custom MVC architecture, no framework)
- Frontend: HTML5, CSS3, JavaScript (Vanilla)
- Database: MySQL (InnoDB, UTF-8)
- Architecture: MVC Pattern

---

## 🏗️ Architecture & Structure

### Entry Point
- **`index.php`** (root) → Redirects to `public/index.php`
- **`public/index.php`** → Front controller that:
  - Starts session
  - Loads configuration files
  - Handles routing via query parameters (`?controller=X&action=Y`)
  - Instantiates controllers and calls actions

### Routing System
- **Simple Query-Based Routing**: Uses `$_GET['controller']` and `$_GET['action']`
- **Router Class** (`app/core/Router.php`): Exists but not actively used; system falls back to query string routing
- **Controller Naming**: `{ControllerName}Controller` (e.g., `HomeController`)
- **Action Methods**: Public methods in controllers

### MVC Pattern Implementation

#### **Models** (`app/models/`)
All models follow similar pattern:
- Use PDO with prepared statements (SQL injection protection)
- Singleton database connection via `getDBConnection()`
- CRUD operations
- No ORM - direct SQL queries

**Key Models:**
1. **User.php** - User authentication, profile management
2. **PlantCatalog.php** - Plant database with filtering/search
3. **UserPlant.php** - Personal plant collections with care tracking
4. **PlantCareEvent.php** - History of watering/fertilizing events
5. **Product.php** - Marketplace products
6. **Order.php** - E-commerce orders with transaction handling
7. **Post.php** - Social feed posts with likes
8. **Comment.php** - Post comments
9. **Notification.php** - User notifications system
10. **WeatherService.php** - Weather API integration (currently mocked)

#### **Controllers** (`app/controllers/`)
All extend base `Controller` class with helper methods:
- `view()` - Renders views with layout
- `json()` - Returns JSON responses
- `redirect()` - URL redirection
- `requireAuth()` - Authentication check
- `requireAdmin()` - Admin role check
- `getCurrentUser()` - Gets logged-in user

**Controllers:**
1. **HomeController** - Landing page
2. **AuthController** - Login, register, logout
3. **PlantCatalogController** - Browse/search plant catalog, add to collection
4. **UserPlantController** - Manage personal plants, mark care events
5. **DashboardController** - Overview with care reminders, weather
6. **MarketplaceController** - Product browsing, cart, checkout, orders
7. **SocialController** - Posts, comments, likes, profiles
8. **NotificationController** - View/manage notifications
9. **AdminController** - CRUD for plants/products, content moderation

#### **Views** (`app/views/`)
- **Layout System**: `header.php` + `footer.php` wrap all views
- **View Structure**: Organized by feature (home, auth, plant_catalog, etc.)
- **Data Passing**: Controllers pass data via `$data` array, extracted in views

---

## 🗄️ Database Schema

### Core Tables

1. **users**
   - Authentication (email/password)
   - Profile (username, bio, avatar_url, city)
   - Role-based access (user/admin)

2. **plant_catalog**
   - Plant information (common/scientific names, description)
   - Care requirements (difficulty, light, water, humidity, temperature)
   - Default care intervals (watering, fertilizing)
   - Guides for seeds vs mature plants

3. **user_plants**
   - Links users to plants in catalog
   - Custom care intervals (overrides defaults)
   - Last care dates (watering, fertilizing)
   - Personal notes, nickname, room location
   - Tracks if from marketplace

4. **plant_care_events**
   - Historical record of care actions
   - Event types: watering, fertilizing, repotting, other
   - Notes for each event

5. **products** (Marketplace)
   - Categories: seed, plant, pot, soil, fertilizer, accessory
   - Stock management
   - Links to plant_catalog for plant/seed products

6. **orders** & **order_items**
   - Order management with status tracking
   - Transaction handling (stock updates, totals)
   - Shipping address

7. **posts** (Social)
   - User-generated content
   - Post types: normal, help, article
   - Can link to user_plants

8. **post_likes** & **comments**
   - Social interactions
   - Unique constraint on likes (one per user/post)

9. **notifications**
   - User notifications with read/unread status
   - Types: plant_watering, post_liked, post_commented, help_replied
   - Link URLs for navigation

10. **plant_photos** (Schema exists, not actively used in code)

### Relationships
- Foreign keys with CASCADE deletes
- Indexes on frequently queried columns
- Proper normalization

---

## 🔐 Security Features

### Implemented
- ✅ Password hashing (PHP `password_hash()`)
- ✅ Prepared statements (PDO) - SQL injection protection
- ✅ Session-based authentication
- ✅ Role-based access control (user/admin)
- ✅ Input validation in controllers
- ✅ CSRF protection: **NOT IMPLEMENTED** (vulnerability)

### Missing/Weaknesses
- ❌ CSRF tokens for forms
- ❌ File upload validation (if implemented)
- ❌ XSS protection: Uses `htmlspecialchars()` in some views but not consistently
- ❌ Rate limiting
- ❌ Password strength requirements
- ❌ Email verification

---

## 🎨 Frontend Architecture

### CSS (`public/assets/css/style.css`)
- CSS Variables for theming
- Mobile-responsive design
- Component-based styling (buttons, forms, cards)
- Hamburger menu for mobile
- Modern, clean design

### JavaScript (`public/assets/js/main.js`)
- Vanilla JS (no frameworks)
- Features:
  - Auto-refresh notification count (every 30s)
  - Form validation
  - Smooth scrolling
  - Hamburger menu toggle
- No AJAX for most operations (page reloads)

### View Structure
- Consistent layout via header/footer
- French language interface
- Semantic HTML
- Accessible navigation

---

## 🔄 Key Features & Workflows

### 1. User Authentication
- Registration with email/username/password
- Login with session creation
- Auto-login after registration
- Session stores: user_id, email, username, role

### 2. Plant Catalog
- Browse with filters (difficulty, light, water, beginners)
- Search by name/description
- Pagination (12 per page)
- Detail pages with care guides
- "Add to Collection" functionality

### 3. Personal Plant Management
- Add plants from catalog or marketplace
- Track watering/fertilizing dates
- Custom care intervals
- Calculate next care dates
- Mark care events (creates history)
- Dashboard shows plants needing care

### 4. Marketplace
- Product browsing by category
- Shopping cart (session-based)
- Checkout process
- Order creation with stock management
- Auto-add purchased plants to collection
- Order history

### 5. Social Features
- Create posts (text + optional image URL)
- Like/unlike posts
- Comment on posts
- User profiles
- Link posts to user plants
- Post types (normal, help, article)

### 6. Notifications
- Automatic reminders (watering dates)
- Social notifications (likes, comments)
- Unread count badge
- Mark as read functionality

### 7. Weather Integration
- Currently uses mock data
- Can integrate OpenWeatherMap API
- Provides care recommendations based on weather
- Checks temperature/humidity against plant needs

### 8. Admin Panel
- Manage plant catalog (CRUD)
- Manage products (CRUD)
- Moderate posts/comments (delete)

---

## 📊 Data Flow Examples

### Adding Plant to Collection
1. User clicks "Add to Collection" on plant detail page
2. `PlantCatalogController::addToCollection()` receives POST
3. Checks if user already owns plant
4. `UserPlant::create()` inserts into `user_plants` table
5. Redirects to user's plant list

### Marking Plant as Watered
1. User clicks "Mark as Watered" button
2. `UserPlantController::markWatered()` receives POST
3. Updates `last_watering_date` in `user_plants`
4. Creates event in `plant_care_events`
5. Creates notification for next watering date
6. Redirects back to plant detail

### Creating Order
1. User proceeds to checkout
2. `MarketplaceController::checkout()` validates cart
3. `Order::create()`:
   - Begins transaction
   - Calculates total
   - Creates order record
   - Creates order_items
   - Updates product stock
   - Commits transaction
4. Adds purchased plants to user collection
5. Clears cart session
6. Redirects to success page

---

## 🐛 Known Issues & Limitations

### Code Issues
1. **Router not used**: Router class exists but system uses query strings
2. **Inconsistent error handling**: Some controllers don't handle errors gracefully
3. **No pagination in some lists**: Social feed, notifications could benefit
4. **Photo upload not implemented**: `plant_photos` table exists but unused
5. **Image URLs**: Currently expects external URLs, no file upload

### Security Concerns
1. **No CSRF protection**: Forms vulnerable to CSRF attacks
2. **XSS risk**: Not all user input is escaped
3. **No rate limiting**: Vulnerable to brute force
4. **Session security**: No session regeneration on login

### Functionality Gaps
1. **Email notifications**: Not implemented
2. **Real payment processing**: Simulated only
3. **File uploads**: Not implemented (images via URLs)
4. **Search limitations**: Basic LIKE queries, no full-text search
5. **No API**: All interactions via page loads

---

## 🔧 Configuration

### Database (`config/database.php`)
```php
DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHARSET
```

### Application (`config/constants.php`)
- `BASE_URL` - Application base URL
- Path constants (ROOT_PATH, APP_PATH, VIEWS_PATH, PUBLIC_PATH)
- Role constants (ROLE_USER, ROLE_ADMIN)
- Plant care constants (difficulty, light, water, humidity levels)
- Event type constants
- Notification type constants
- Order status constants

### Autoloader (`config/autoload.php`)
- Simple autoloader for models, controllers, core classes
- Searches in: models/, controllers/, core/

---

## 📁 File Organization

```
Plants/
├── app/
│   ├── controllers/     # Business logic
│   ├── models/          # Data access layer
│   ├── views/           # Presentation layer
│   └── core/            # Base classes (Controller, Router)
├── config/              # Configuration files
├── database/            # SQL scripts
├── public/              # Public entry point + assets
│   ├── index.php        # Front controller
│   └── assets/
│       ├── css/
│       ├── js/
│       └── uploads/     # For future file uploads
└── index.php            # Redirects to public/
```

---

## 🚀 Extension Points

### Easy to Add:
1. **New controllers**: Follow existing pattern
2. **New models**: Use PDO pattern from existing models
3. **New views**: Use layout system
4. **New routes**: Add to query string routing
5. **New notification types**: Add constant + handling

### Requires More Work:
1. **File uploads**: Need upload handler, validation, storage
2. **Email system**: SMTP configuration, email templates
3. **API endpoints**: Restructure for JSON responses
4. **Real payment**: Integrate payment gateway
5. **Advanced search**: Full-text search, filters
6. **Image processing**: Resize, optimize uploads

---

## 📝 Code Quality Notes

### Strengths
- ✅ Clean MVC separation
- ✅ Consistent naming conventions
- ✅ Prepared statements throughout
- ✅ Good database normalization
- ✅ Responsive design
- ✅ Well-documented (README, comments)

### Areas for Improvement
- ⚠️ No dependency injection
- ⚠️ Models tightly coupled to database
- ⚠️ No service layer (business logic in controllers)
- ⚠️ Limited error handling
- ⚠️ No logging system
- ⚠️ No unit tests
- ⚠️ Mixed French/English in code

---

## 🎯 Recommended Modifications Priority

### High Priority (Security)
1. Add CSRF protection to all forms
2. Implement XSS escaping consistently
3. Add session security (regeneration, secure flags)
4. Implement rate limiting

### Medium Priority (Features)
1. Implement file upload for images
2. Add email notifications
3. Improve error handling
4. Add pagination where missing
5. Implement photo gallery for user plants

### Low Priority (Enhancements)
1. Add API endpoints
2. Implement full-text search
3. Add unit tests
4. Refactor to use Router class properly
5. Add caching layer

---

## 🔍 Quick Reference

### Common URLs
- Home: `/?controller=home&action=index` or `/`
- Catalog: `/?controller=plantCatalog&action=index`
- Dashboard: `/?controller=dashboard&action=index`
- My Plants: `/?controller=userPlant&action=index`
- Social: `/?controller=social&action=index`
- Marketplace: `/?controller=marketplace&action=index`
- Admin: `/?controller=admin&action=plants`

### Default Accounts (from seed_data.sql)
- Admin: `admin@plants.com` / `admin123`
- User: `user@example.com` / `user123`

### Key Session Variables
- `$_SESSION['user_id']`
- `$_SESSION['user_email']`
- `$_SESSION['user_username']`
- `$_SESSION['user_role']`
- `$_SESSION['cart']` (marketplace)

---

**Last Updated**: Analysis completed after deep code review
**Project Status**: Functional, production-ready with noted security improvements needed

