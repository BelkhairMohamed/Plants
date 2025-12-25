<?php
/**
 * Dashboard Controller
 */

class DashboardController extends Controller {
    public function __construct() {
        $this->requireAuth();
    }
    
    public function index() {
        $userPlantModel = new UserPlant();
        $postModel = new Post();
        $careEventModel = new PlantCareEvent();
        $weatherService = new WeatherService();
        
        // Get plants needing care
        $plantsNeedingWater = $userPlantModel->getPlantsNeedingWater($_SESSION['user_id']);
        $plantsNeedingFertilizer = $userPlantModel->getPlantsNeedingFertilizer($_SESSION['user_id']);
        
        // Get recent activity
        $recentPosts = $postModel->getAll(5, 0);
        $recentCareEvents = $careEventModel->getRecentByUserId($_SESSION['user_id'], 5);
        
        // Get statistics for charts
        $plantsByRoom = $userPlantModel->getStatsByRoom($_SESSION['user_id']);
        $totalPlants = $userPlantModel->getTotalCount($_SESSION['user_id']);
        $plantsByMonth = $userPlantModel->getPlantsByMonth($_SESSION['user_id'], 6);
        $careEventsByType = $careEventModel->getCareEventsByType($_SESSION['user_id'], 30);
        $careEventsByDay = $careEventModel->getCareEventsByDay($_SESSION['user_id'], 30);
        
        // Get weather data if user has city set
        $user = $this->getCurrentUser();
        $weather = null;
        $weatherRecommendations = [];
        
        if ($user && !empty($user['city'])) {
            $weather = $weatherService->getWeatherByCity($user['city']);
            
            // Get recommendations for plants needing care
            if ($weather && !empty($plantsNeedingWater)) {
                $samplePlant = $userPlantModel->findById($plantsNeedingWater[0]['id']);
                if ($samplePlant) {
                    $weatherRecommendations = $weatherService->getCareRecommendations($weather, [
                        'temperature_min' => $samplePlant['temperature_min'],
                        'temperature_max' => $samplePlant['temperature_max'],
                        'humidity_preference' => $samplePlant['humidity_preference']
                    ]);
                }
            }
        }
        
        $this->view('dashboard/index', [
            'plantsNeedingWater' => $plantsNeedingWater,
            'plantsNeedingFertilizer' => $plantsNeedingFertilizer,
            'recentPosts' => $recentPosts,
            'recentCareEvents' => $recentCareEvents,
            'weather' => $weather,
            'weatherRecommendations' => $weatherRecommendations,
            'plantsByRoom' => $plantsByRoom,
            'totalPlants' => $totalPlants,
            'plantsByMonth' => $plantsByMonth,
            'careEventsByType' => $careEventsByType,
            'careEventsByDay' => $careEventsByDay
        ]);
    }
    
    public function adminDashboard() {
        $this->requireAdmin();
        
        // Get all user dashboard data (for admin's own dashboard)
        $userPlantModel = new UserPlant();
        $postModel = new Post();
        $careEventModel = new PlantCareEvent();
        $weatherService = new WeatherService();
        $userModel = new User();
        $orderModel = new Order();
        $productModel = new Product();
        $plantCatalogModel = new PlantCatalog();
        
        // User dashboard data (admin's own)
        $plantsNeedingWater = $userPlantModel->getPlantsNeedingWater($_SESSION['user_id']);
        $plantsNeedingFertilizer = $userPlantModel->getPlantsNeedingFertilizer($_SESSION['user_id']);
        $recentPosts = $postModel->getAll(5, 0);
        $recentCareEvents = $careEventModel->getRecentByUserId($_SESSION['user_id'], 5);
        $plantsByRoom = $userPlantModel->getStatsByRoom($_SESSION['user_id']);
        $totalPlants = $userPlantModel->getTotalCount($_SESSION['user_id']);
        $plantsByMonth = $userPlantModel->getPlantsByMonth($_SESSION['user_id'], 6);
        $careEventsByType = $careEventModel->getCareEventsByType($_SESSION['user_id'], 30);
        $careEventsByDay = $careEventModel->getCareEventsByDay($_SESSION['user_id'], 30);
        
        // Get weather data
        $user = $this->getCurrentUser();
        $weather = null;
        $weatherRecommendations = [];
        
        if ($user && !empty($user['city'])) {
            $weather = $weatherService->getWeatherByCity($user['city']);
            
            if ($weather && !empty($plantsNeedingWater)) {
                $samplePlant = $userPlantModel->findById($plantsNeedingWater[0]['id']);
                if ($samplePlant) {
                    $weatherRecommendations = $weatherService->getCareRecommendations($weather, [
                        'temperature_min' => $samplePlant['temperature_min'],
                        'temperature_max' => $samplePlant['temperature_max'],
                        'humidity_preference' => $samplePlant['humidity_preference']
                    ]);
                }
            }
        }
        
        // Admin-specific statistics
        $db = getDBConnection();
        
        // Total users
        $stmt = $db->query("SELECT COUNT(*) as count FROM users");
        $totalUsers = $stmt->fetch()['count'];
        
        // New users this month
        $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $newUsersThisMonth = $stmt->fetch()['count'];
        
        // Total posts
        $stmt = $db->query("SELECT COUNT(*) as count FROM posts");
        $totalPosts = $stmt->fetch()['count'];
        
        // Total orders
        $stmt = $db->query("SELECT COUNT(*) as count FROM orders");
        $totalOrders = $stmt->fetch()['count'];
        
        // Total revenue
        $stmt = $db->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'");
        $totalRevenue = $stmt->fetch()['total'] ?? 0;
        
        // Total user plants
        $stmt = $db->query("SELECT COUNT(*) as count FROM user_plants");
        $totalUserPlants = $stmt->fetch()['count'];
        
        // Total catalog plants
        $stmt = $db->query("SELECT COUNT(*) as count FROM plant_catalog");
        $totalCatalogPlants = $stmt->fetch()['count'];
        
        // Active users (logged in last 30 days) - approximate
        $stmt = $db->query("
            SELECT COUNT(DISTINCT up.user_id) as count 
            FROM plant_care_events pce
            JOIN user_plants up ON pce.user_plant_id = up.id
            WHERE pce.event_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $activeUsers = $stmt->fetch()['count'];
        
        // User activity by day (last 30 days)
        $stmt = $db->query("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");
        $userActivityByDay = $stmt->fetchAll();
        
        // Posts by day (last 30 days)
        $stmt = $db->query("
            SELECT DATE(created_at) as date, COUNT(*) as count 
            FROM posts 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");
        $postsByDay = $stmt->fetchAll();
        
        // Orders by status
        $stmt = $db->query("
            SELECT status, COUNT(*) as count 
            FROM orders 
            GROUP BY status
        ");
        $ordersByStatus = $stmt->fetchAll();
        
        // Recent user registrations
        $recentUsers = $userModel->getAll(10, 0);
        
        // Recent orders
        $stmt = $db->query("
            SELECT o.*, u.username, u.email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
            LIMIT 10
        ");
        $recentOrders = $stmt->fetchAll();
        
        // System health checks
        $systemHealth = [
            'database' => true, // Assume OK if we got here
            'disk_space' => disk_free_space(__DIR__) > 0,
            'php_version' => version_compare(PHP_VERSION, '8.0.0', '>=')
        ];
        
        $this->view('dashboard/admin', [
            // User dashboard data
            'plantsNeedingWater' => $plantsNeedingWater,
            'plantsNeedingFertilizer' => $plantsNeedingFertilizer,
            'recentPosts' => $recentPosts,
            'recentCareEvents' => $recentCareEvents,
            'weather' => $weather,
            'weatherRecommendations' => $weatherRecommendations,
            'plantsByRoom' => $plantsByRoom,
            'totalPlants' => $totalPlants,
            'plantsByMonth' => $plantsByMonth,
            'careEventsByType' => $careEventsByType,
            'careEventsByDay' => $careEventsByDay,
            // Admin statistics
            'totalUsers' => $totalUsers,
            'newUsersThisMonth' => $newUsersThisMonth,
            'totalPosts' => $totalPosts,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalUserPlants' => $totalUserPlants,
            'totalCatalogPlants' => $totalCatalogPlants,
            'activeUsers' => $activeUsers,
            'userActivityByDay' => $userActivityByDay,
            'postsByDay' => $postsByDay,
            'ordersByStatus' => $ordersByStatus,
            'recentUsers' => $recentUsers,
            'recentOrders' => $recentOrders,
            'systemHealth' => $systemHealth
        ]);
    }
}



