<?php
/**
 * User Plant Controller (Personal Collection)
 */

class UserPlantController extends Controller {
    private $userPlantModel;
    private $plantModel;
    private $careEventModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->userPlantModel = new UserPlant();
        $this->plantModel = new PlantCatalog();
        $this->careEventModel = new PlantCareEvent();
    }
    
    public function index() {
        $userPlants = $this->userPlantModel->findByUserId($_SESSION['user_id']);
        
        // Calculate next watering/fertilizing dates
        foreach ($userPlants as &$plant) {
            $wateringInterval = $plant['custom_watering_interval_days'] ?? $plant['default_watering_interval_days'];
            $fertilizingInterval = $plant['custom_fertilizing_interval_days'] ?? $plant['default_fertilizing_interval_days'];
            
            $lastWatering = $plant['last_watering_date'] ?? $plant['created_at'];
            $lastFertilizing = $plant['last_fertilizing_date'] ?? $plant['created_at'];
            
            $plant['next_watering_date'] = date('Y-m-d', strtotime($lastWatering . " + $wateringInterval days"));
            $plant['next_fertilizing_date'] = date('Y-m-d', strtotime($lastFertilizing . " + $fertilizingInterval days"));
            
            $today = new DateTime();
            $nextWatering = new DateTime($plant['next_watering_date']);
            $nextFertilizing = new DateTime($plant['next_fertilizing_date']);
            
            $plant['days_until_watering'] = $today->diff($nextWatering)->days;
            $plant['days_until_fertilizing'] = $today->diff($nextFertilizing)->days;
            $plant['needs_watering'] = $nextWatering <= $today;
            $plant['needs_fertilizing'] = $nextFertilizing <= $today;
        }
        
        $this->view('user_plant/index', ['plants' => $userPlants]);
    }
    
    public function detail() {
        $id = intval($_GET['id'] ?? 0);
        
        if (!$id) {
            $this->redirect('/?controller=userPlant&action=index');
            return;
        }
        
        $plant = $this->userPlantModel->findById($id);
        
        if (!$plant || $plant['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/?controller=userPlant&action=index');
            return;
        }
        
        // Get care events
        $events = $this->careEventModel->findByUserPlantId($id);
        
        // Get photos (if you implement photo upload)
        // $photos = $this->photoModel->findByUserPlantId($id);
        
        // Calculate next care dates
        $wateringInterval = $plant['custom_watering_interval_days'] ?? $plant['default_watering_interval_days'];
        $fertilizingInterval = $plant['custom_fertilizing_interval_days'] ?? $plant['default_fertilizing_interval_days'];
        
        $lastWatering = $plant['last_watering_date'] ?? $plant['created_at'];
        $lastFertilizing = $plant['last_fertilizing_date'] ?? $plant['created_at'];
        
        $plant['next_watering_date'] = date('Y-m-d', strtotime($lastWatering . " + $wateringInterval days"));
        $plant['next_fertilizing_date'] = date('Y-m-d', strtotime($lastFertilizing . " + $fertilizingInterval days"));
        
        $this->view('user_plant/detail', [
            'plant' => $plant,
            'events' => $events
        ]);
    }
    
    public function markWatered() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            
            if ($id) {
                $plant = $this->userPlantModel->findById($id);
                if ($plant && $plant['user_id'] == $_SESSION['user_id']) {
                    $this->userPlantModel->markWatered($id);
                    $this->careEventModel->create($id, EVENT_WATERING, date('Y-m-d'));
                    
                    // Create notification reminder for next time
                    $notificationModel = new Notification();
                    $wateringInterval = $plant['custom_watering_interval_days'] ?? $plant['default_watering_interval_days'];
                    $nextDate = date('Y-m-d', strtotime("+ $wateringInterval days"));
                    $notificationModel->create(
                        $_SESSION['user_id'],
                        NOTIF_PLANT_WATERING,
                        "Don't forget to water your " . ($plant['nickname_for_plant'] ?? $plant['common_name']) . " around $nextDate",
                        "/?controller=userPlant&action=detail&id=$id"
                    );
                }
            }
        }
        
        $this->redirect('/?controller=userPlant&action=detail&id=' . $id);
    }
    
    public function markFertilized() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            
            if ($id) {
                $plant = $this->userPlantModel->findById($id);
                if ($plant && $plant['user_id'] == $_SESSION['user_id']) {
                    $this->userPlantModel->markFertilized($id);
                    $this->careEventModel->create($id, EVENT_FERTILIZING, date('Y-m-d'));
                }
            }
        }
        
        $this->redirect('/?controller=userPlant&action=detail&id=' . $id);
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            
            if ($id) {
                $plant = $this->userPlantModel->findById($id);
                if ($plant && $plant['user_id'] == $_SESSION['user_id']) {
                    $data = [
                        'nickname_for_plant' => $_POST['nickname'] ?? null,
                        'room_location' => $_POST['room_location'] ?? null,
                        'custom_watering_interval_days' => !empty($_POST['watering_interval']) ? intval($_POST['watering_interval']) : null,
                        'custom_fertilizing_interval_days' => !empty($_POST['fertilizing_interval']) ? intval($_POST['fertilizing_interval']) : null,
                        'notes' => $_POST['notes'] ?? null
                    ];
                    
                    $this->userPlantModel->update($id, $data);
                }
            }
        }
        
        $this->redirect('/?controller=userPlant&action=detail&id=' . $id);
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            
            if ($id) {
                $plant = $this->userPlantModel->findById($id);
                if ($plant && $plant['user_id'] == $_SESSION['user_id']) {
                    $this->userPlantModel->delete($id);
                }
            }
        }
        
        $this->redirect('/?controller=userPlant&action=index');
    }
}




