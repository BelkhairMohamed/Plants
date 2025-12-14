<?php
/**
 * Plant Catalog Controller
 */

class PlantCatalogController extends Controller {
    private $plantModel;
    
    public function __construct() {
        $this->plantModel = new PlantCatalog();
    }
    
    public function index() {
        $filters = [
            'difficulty_level' => $_GET['difficulty'] ?? null,
            'light_requirement' => $_GET['light'] ?? null,
            'water_requirement' => $_GET['water'] ?? null,
            'recommended_for_beginners' => isset($_GET['beginners']) ? true : null,
            'search' => $_GET['search'] ?? null
        ];
        
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $plants = $this->plantModel->getAll($filters, $limit, $offset);
        
        // Get first image for each plant from plant_catalog_images table
        foreach ($plants as &$plant) {
            $plantImages = $this->plantModel->getImages($plant['id']);
            if (!empty($plantImages)) {
                // Use first image from plant_catalog_images
                $plant['image_url'] = $plantImages[0]['image_url'];
            }
            // If no images in plant_catalog_images, keep the original image_url from plant_catalog
        }
        unset($plant); // Break reference
        
        $total = $this->plantModel->count($filters);
        $totalPages = ceil($total / $limit);
        
        $this->view('plant_catalog/index', [
            'plants' => $plants,
            'filters' => $filters,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }
    
    public function detail() {
        $id = intval($_GET['id'] ?? 0);
        
        if (!$id) {
            $this->redirect('/?controller=plantCatalog&action=index');
            return;
        }
        
        $plant = $this->plantModel->findById($id);
        
        if (!$plant) {
            http_response_code(404);
            require_once VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Get all images for this plant
        $plantImages = $this->plantModel->getImages($id);
        
        // If no images in plant_catalog_images table, use image_url as fallback
        if (empty($plantImages) && !empty($plant['image_url'])) {
            $plantImages = [['image_url' => $plant['image_url']]];
        }
        
        // Check if user already owns this plant
        $userOwnsPlant = false;
        if (isset($_SESSION['user_id'])) {
            $userPlantModel = new UserPlant();
            $userOwnsPlant = $userPlantModel->userOwnsPlant($_SESSION['user_id'], $id);
        }
        
        $this->view('plant_catalog/detail', [
            'plant' => $plant,
            'plantImages' => $plantImages,
            'userOwnsPlant' => $userOwnsPlant
        ]);
    }
    
    public function addToCollection() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plantCatalogId = intval($_POST['plant_catalog_id'] ?? 0);
            $acquisitionType = $_POST['acquisition_type'] ?? 'unknown';
            $nickname = $_POST['nickname'] ?? null;
            $roomLocation = $_POST['room_location'] ?? null;
            
            if (!$plantCatalogId) {
                $this->redirect('/?controller=plantCatalog&action=index');
                return;
            }
            
            $userPlantModel = new UserPlant();
            
            // Check if already in collection
            if ($userPlantModel->userOwnsPlant($_SESSION['user_id'], $plantCatalogId)) {
                $this->redirect('/?controller=userPlant&action=index');
                return;
            }
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'plant_catalog_id' => $plantCatalogId,
                'nickname_for_plant' => $nickname,
                'acquisition_type' => $acquisitionType,
                'room_location' => $roomLocation,
                'is_from_marketplace' => false
            ];
            
            if ($userPlantModel->create($data)) {
                $this->redirect('/?controller=userPlant&action=index');
            } else {
                $error = 'Failed to add plant to collection';
                $plant = $this->plantModel->findById($plantCatalogId);
                $this->view('plant_catalog/detail', ['plant' => $plant, 'error' => $error]);
            }
        } else {
            $this->redirect('/?controller=plantCatalog&action=index');
        }
    }
}



