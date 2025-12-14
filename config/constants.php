<?php
/**
 * Application Constants
 */

// Base URL
define('BASE_URL', 'http://localhost/Plants-main');

// Paths
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// User Roles
define('ROLE_USER', 'user');
define('ROLE_ADMIN', 'admin');

// Plant Difficulty Levels
define('DIFFICULTY_BEGINNER', 'beginner');
define('DIFFICULTY_INTERMEDIATE', 'intermediate');
define('DIFFICULTY_ADVANCED', 'advanced');

// Light Requirements
define('LIGHT_LOW', 'low');
define('LIGHT_MEDIUM', 'medium');
define('LIGHT_HIGH', 'high');

// Water Requirements
define('WATER_LOW', 'low');
define('WATER_MEDIUM', 'medium');
define('WATER_HIGH', 'high');

// Humidity Preferences
define('HUMIDITY_LOW', 'low');
define('HUMIDITY_MEDIUM', 'medium');
define('HUMIDITY_HIGH', 'high');

// Event Types
define('EVENT_WATERING', 'watering');
define('EVENT_FERTILIZING', 'fertilizing');
define('EVENT_REPOTTING', 'repotting');
define('EVENT_OTHER', 'other');

// Post Types
define('POST_TYPE_NORMAL', 'normal');
define('POST_TYPE_HELP', 'help');
define('POST_TYPE_ARTICLE', 'article');

// Notification Types
define('NOTIF_PLANT_WATERING', 'plant_watering');
define('NOTIF_POST_LIKED', 'post_liked');
define('NOTIF_POST_COMMENTED', 'post_commented');
define('NOTIF_HELP_REPLIED', 'help_replied');

// Order Status
define('ORDER_STATUS_PENDING', 'pending');
define('ORDER_STATUS_PROCESSING', 'processing');
define('ORDER_STATUS_SHIPPED', 'shipped');
define('ORDER_STATUS_DELIVERED', 'delivered');
define('ORDER_STATUS_CANCELLED', 'cancelled');

