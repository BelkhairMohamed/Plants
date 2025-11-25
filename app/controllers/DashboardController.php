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
            'weatherRecommendations' => $weatherRecommendations
        ]);
    }
}



