<?php
/**
 * Home Controller
 */

class HomeController extends Controller {
    public function index() {
        $productModel = new Product();
        $plantModel = new PlantCatalog();
        
        // Get best sellers (products with most stock sold - for now, get by category)
        $bestSellers = $productModel->getAll(['category' => 'plant'], 8, 0);
        
        // Update images for bestSellers from plant_catalog_images
        foreach ($bestSellers as &$product) {
            if (!empty($product['related_plant_catalog_id'])) {
                $plantImages = $plantModel->getImages($product['related_plant_catalog_id']);
                if (!empty($plantImages)) {
                    $product['image_url'] = $plantImages[0]['image_url'];
                }
            }
        }
        unset($product);
        
        $this->view('home/index', [
            'bestSellers' => $bestSellers
        ]);
    }
}



