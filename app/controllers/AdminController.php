<?php
/**
 * Admin Controller
 */

class AdminController extends Controller {
    public function __construct() {
        $this->requireAdmin();
    }
    
    public function plants() {
        $plantModel = new PlantCatalog();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $id = intval($_POST['id'] ?? 0);
                if ($id) {
                    $plantModel->delete($id);
                }
            } else {
                // Create or update
                $data = [
                    'common_name' => $_POST['common_name'] ?? '',
                    'scientific_name' => $_POST['scientific_name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'difficulty_level' => $_POST['difficulty_level'] ?? 'beginner',
                    'light_requirement' => $_POST['light_requirement'] ?? 'medium',
                    'water_requirement' => $_POST['water_requirement'] ?? 'medium',
                    'humidity_preference' => $_POST['humidity_preference'] ?? 'medium',
                    'temperature_min' => !empty($_POST['temperature_min']) ? intval($_POST['temperature_min']) : null,
                    'temperature_max' => !empty($_POST['temperature_max']) ? intval($_POST['temperature_max']) : null,
                    'image_url' => $_POST['image_url'] ?? '',
                    'recommended_for_beginners' => isset($_POST['recommended_for_beginners']),
                    'default_watering_interval_days' => intval($_POST['default_watering_interval_days'] ?? 7),
                    'default_fertilizing_interval_days' => intval($_POST['default_fertilizing_interval_days'] ?? 30),
                    'seed_guide' => $_POST['seed_guide'] ?? '',
                    'mature_plant_guide' => $_POST['mature_plant_guide'] ?? ''
                ];
                
                $id = intval($_POST['id'] ?? 0);
                if ($id) {
                    $plantModel->update($id, $data);
                } else {
                    $plantModel->create($data);
                }
            }
            
            $this->redirect('/?controller=admin&action=plants');
            return;
        }
        
        $plants = $plantModel->getAll([], 100);
        $this->view('admin/plants', ['plants' => $plants]);
    }
    
    public function products() {
        $productModel = new Product();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $id = intval($_POST['id'] ?? 0);
                if ($id) {
                    $productModel->delete($id);
                }
            } else {
                // Create or update
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'category' => $_POST['category'] ?? 'plant',
                    'price' => floatval($_POST['price'] ?? 0),
                    'image_url' => $_POST['image_url'] ?? '',
                    'stock' => intval($_POST['stock'] ?? 0),
                    'is_seed' => isset($_POST['is_seed']),
                    'related_plant_catalog_id' => !empty($_POST['related_plant_catalog_id']) ? intval($_POST['related_plant_catalog_id']) : null
                ];
                
                $id = intval($_POST['id'] ?? 0);
                if ($id) {
                    $productModel->update($id, $data);
                } else {
                    $productModel->create($data);
                }
            }
            
            $this->redirect('/?controller=admin&action=products');
            return;
        }
        
        $products = $productModel->getAll([], 100);
        $plantModel = new PlantCatalog();
        $plants = $plantModel->getAll([], 100);
        
        $this->view('admin/products', [
            'products' => $products,
            'plants' => $plants
        ]);
    }
    
    public function moderate() {
        $postModel = new Post();
        $commentModel = new Comment();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_post'])) {
                $id = intval($_POST['post_id'] ?? 0);
                if ($id) {
                    $postModel->delete($id);
                }
            } elseif (isset($_POST['delete_comment'])) {
                $id = intval($_POST['comment_id'] ?? 0);
                if ($id) {
                    $commentModel->delete($id);
                }
            }
            
            $this->redirect('/?controller=admin&action=moderate');
            return;
        }
        
        $posts = $postModel->getAll(50, 0);
        $this->view('admin/moderate', ['posts' => $posts]);
    }
}



