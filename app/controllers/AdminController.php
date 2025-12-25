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
        $message = null;
        $messageType = 'success';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                if ($_POST['action'] === 'delete') {
                    $id = intval($_POST['id'] ?? 0);
                    if ($id) {
                        if ($plantModel->delete($id)) {
                            $message = 'Plante supprimée avec succès';
                        } else {
                            $message = 'Erreur lors de la suppression';
                            $messageType = 'error';
                        }
                    }
                } elseif ($_POST['action'] === 'add_image') {
                    // Add image to plant
                    $plantId = intval($_POST['plant_id'] ?? 0);
                    $imageUrl = trim($_POST['image_url'] ?? '');
                    if ($plantId && $imageUrl) {
                        if ($plantModel->addImage($plantId, $imageUrl)) {
                            $message = 'Image ajoutée avec succès';
                        } else {
                            $message = 'Erreur: La table plant_catalog_images n\'existe peut-être pas. Exécutez la migration SQL.';
                            $messageType = 'error';
                        }
                    } else {
                        $message = 'Veuillez entrer une URL d\'image valide';
                        $messageType = 'error';
                    }
                } elseif ($_POST['action'] === 'delete_image') {
                    // Delete specific image
                    $imageId = intval($_POST['image_id'] ?? 0);
                    if ($imageId) {
                        if ($plantModel->deleteImage($imageId)) {
                            $message = 'Image supprimée avec succès';
                        } else {
                            $message = 'Erreur lors de la suppression de l\'image';
                            $messageType = 'error';
                        }
                    }
                } elseif ($_POST['action'] === 'create' || $_POST['action'] === 'update') {
                    // Create or update plant
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
                    if ($id && $_POST['action'] === 'update') {
                        $plantModel->update($id, $data);
                        // If main image_url is provided and not already in images, add it
                        if (!empty($data['image_url'])) {
                            $existingImages = $plantModel->getImages($id);
                            $imageExists = false;
                            foreach ($existingImages as $img) {
                                if ($img['image_url'] === $data['image_url']) {
                                    $imageExists = true;
                                    break;
                                }
                            }
                            if (!$imageExists) {
                                $plantModel->addImage($id, $data['image_url'], 0);
                            }
                        }
                        // Handle category assignments
                        if (isset($_POST['categories']) && is_array($_POST['categories'])) {
                            $categoryIds = array_filter(array_map('intval', $_POST['categories']));
                            $plantModel->assignCategories($id, $categoryIds);
                        } else {
                            // If no categories selected, remove all assignments
                            $plantModel->assignCategories($id, []);
                        }
                    } else {
                        $plantId = $plantModel->create($data);
                        // If main image_url is provided, add it as first image
                        if (!empty($data['image_url']) && $plantId) {
                            $plantModel->addImage($plantId, $data['image_url'], 0);
                        }
                        // Handle category assignments for new plant
                        if (isset($_POST['categories']) && is_array($_POST['categories'])) {
                            $categoryIds = array_filter(array_map('intval', $_POST['categories']));
                            $plantModel->assignCategories($plantId, $categoryIds);
                        }
                    }
                }
            }
            
            $this->redirect('/?controller=admin&action=plants');
            return;
        }
        
        $plants = $plantModel->getAll([], 100);
        
        // Get images and categories for each plant
        $plantsWithImages = [];
        foreach ($plants as $plant) {
            $plant['images'] = $plantModel->getImages($plant['id']);
            $plant['categories'] = $plantModel->getPlantCategories($plant['id']);
            $plantsWithImages[] = $plant;
        }
        
        // Get all available categories for the form
        $allCategories = $plantModel->getAllCategories();
        
        $this->view('admin/plants', [
            'plants' => $plantsWithImages,
            'allCategories' => $allCategories,
            'message' => $message ?? null,
            'messageType' => $messageType ?? 'success'
        ]);
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
                $category = $_POST['category'] ?? 'plant';
                // If is_seed checkbox is checked, override category to 'seed'
                if (isset($_POST['is_seed']) && $_POST['is_seed']) {
                    $category = 'seed';
                }
                
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'category' => $category,
                    'price' => floatval($_POST['price'] ?? 0),
                    'image_url' => $_POST['image_url'] ?? '',
                    'stock' => intval($_POST['stock'] ?? 0),
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



