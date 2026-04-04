<?php
namespace App\Controllers;

use App\Services\Interfaces\Yummy\IRestaurantService;
use App\Services\Interfaces\Yummy\IChefService;
use App\Services\Interfaces\Yummy\IRestaurantSessionService;
use App\Models\Yummy\RestaurantModel;
use App\Models\Yummy\RestaurantSessionModel;

use App\Services\PageElementService;
use App\ViewModels\PageElementViewModel;

use Exception; 

class RestaurantController {
    private IRestaurantService $service;
    private IChefService $chefService;
    private IRestaurantSessionService $sessionService;
    private PageElementService $pageElementService;

    public function __construct(IRestaurantService $service, IChefService $chefService, IRestaurantSessionService $sessionService, PageElementService $pageElementService) {
        $this->service = $service;
        $this->chefService = $chefService;
        $this->sessionService = $sessionService;
        $this->pageElementService = $pageElementService;
    }

    public function index() {
        $vm = $this->buildPageVM('yummy');
        $restaurants = $this->service->getAllRestaurants();
        include __DIR__ . '/../Views/event/yummyEvent/index.php';
    }

    public function adminIndex() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $vm = $this->buildPageVM('yummy');
        $restaurants = $this->service->getAllRestaurants();
        $chefs = $this->chefService->getAllChefs();
        include __DIR__ . '/../Views/admin/yummy/index.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $fileName = $this->service->uploadImage($_FILES['image_file'] ?? null);
    
                if ($this->service->processNewRestaurant($_POST, $fileName)) {
                    header('Location: /admin/yummy?status=created');
                    exit;
                }

                throw new Exception("Could not save restaurant to database.");
            } catch (Exception $e) {
                // Log error and pass message to view
                error_log("Store Restaurant Error: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        // to show create form 
        $chefs = $this->chefService->getAllChefs();
        $restaurant = new RestaurantModel();
        include __DIR__ . '/../Views/admin/yummy/createRestaurant.php';
    }


    public function update($vars) {
        $id = (int)$vars['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $newImage = $this->service->uploadImage($_FILES['image_file'] ?? null);
                    
                if ($this->service->processUpdateRestaurant($id, $_POST, $newImage)) {
                    header('Location: /admin/yummy?status=updated');
                    exit;
                }
                throw new Exception("Update failed.");
            } catch (Exception $e) {
                error_log("Update Restaurant Error: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        // preparing data for the edit form
        $restaurant = $this->service->getRestaurantById($id);
        if (!$restaurant) {
            header('Location: /admin/yummy?error=notfound');
            exit;
        }
        $chefs = $this->chefService->getAllChefs();
        $sessions = $this->sessionService->getAvailableSessions($id);
        include __DIR__ . '/../Views/admin/yummy/editRestaurant.php';
    }

    public function delete($vars) {
        try {
            $id = (int)$vars['id'];
            if ($this->service->deleteRestaurant($id)) {
                header('Location: /admin/yummy?status=deleted');
                exit;
            }
        } catch (Exception $e) {
            header('Location: /admin/yummy?status=error&message=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function showDetails($vars) {
        $id = (int)$vars['id'];
        $restaurant = $this->service->getRestaurantById($id);
        $groupedSessions = $this->sessionService->getSessionsGroupedByDate($id);

        if (!$restaurant) {
            http_response_code(404);
            echo "Restaurant not found.";
            return;
        }

        $chef = null;
        if ($restaurant->getChefId()) {
            $chef = $this->chefService->getChefById($restaurant->getChefId());
        }

        include __DIR__ . '/../Views/event/yummyEvent/restaurant.php';
    }

    public function showCreateForm() {
        $chefs = $this->chefService->getAllChefs();
        $restaurant = new RestaurantModel();
        include __DIR__ . '/../Views/admin/yummy/createRestaurant.php';
    }

    public function showEditForm($vars) {
        $id = (int)$vars['id'];
        $restaurant = $this->service->getRestaurantById($id);
        $chefs = $this->chefService->getAllChefs();
        $sessions = $this->sessionService->getAvailableSessions($id);

        if (!$restaurant) {
            header('Location: /admin/yummy?error=notfound');
            exit;
        }

        include __DIR__ . '/../Views/admin/yummy/editRestaurant.php';
    }


    // Reservation handling

    public function addSessions() {
        $restaurantId = (int)$_POST['restaurant_id'];
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $times = array_unique(array_filter($_POST['times'] ?? []));

                if (empty($times)) {
                    header('Location: /admin/yummy/edit/' . $_POST['restaurant_id'] . '?status=error&message=No_times_provided');
                    exit;
                }

                $session = new RestaurantSessionModel();
                $session->setRestaurantId((int)$_POST['restaurant_id']);
                $session->setDate($_POST['session_date']);
                $session->setAvailableSlots((int)$_POST['available_slots']);
                $session->setSelectedTimes($times); 

                if ($this->sessionService->addSessions($session)) {
                    header('Location: /admin/yummy/edit/' . $session->getRestaurantId() . '?status=sessions_added');
                    exit;
                }
            }
        } catch (\Exception $e) {
            header("Location: /admin/yummy/edit/$restaurantId?status=error&message=" . urlencode($e->getMessage()) . "&open_modal=add");
            exit;
    }
        
    }

    public function deleteSession() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionId = (int)$_POST['id'];
            $restaurantId = (int)$_POST['restaurant_id'];

            $this->sessionService->deleteSession($sessionId);

            header("Location: /admin/yummy/edit/$restaurantId?status=session_deleted");
            exit;
        }
    }

    public function updateSession() {
        $restaurantId = isset($_POST['restaurant_id']) ? (int)$_POST['restaurant_id'] : null;
        $sessionId = isset($_POST['session_id']) ? (int)$_POST['session_id'] : null;

        if (!$restaurantId) {
            header("Location: /admin/yummy?status=error");
            exit();
        }

        try {
            $session = new RestaurantSessionModel();
            
            $session->setId((int)$_POST['session_id']);
            $session->setRestaurantId((int)$restaurantId); 
            $session->setDate($_POST['session_date']);
            $session->setStartTime($_POST['start_time']);
            $session->setAvailableSlots((int)$_POST['available_slots']);

            $result = $this->sessionService->updateSession($session);
            
            if ($result) {
                header("Location: /admin/yummy/edit/$restaurantId?status=session_updated");
            } else {
                header("Location: /admin/yummy/edit/$restaurantId?status=error&message=Update failed in database");
            }
        } catch (\Exception $e) {
            header("Location: /admin/yummy/edit/$restaurantId?status=error&message=" . urlencode($e->getMessage()) . "&session_id=$sessionId");
        }
        exit();
    }
    
    public function showReservationForm($vars) {
        $restaurantId = (int)$vars['id'];
        $restaurant = $this->service->getRestaurantById($restaurantId);

        if (!$restaurant) {
            header('Location: /yummy');
            exit;
        }

        $groupedSessions = $this->sessionService->getSessionsGroupedByDate($restaurantId);

        include __DIR__ . '/../Views/event/yummyEvent/reservation.php';
    }


    // Private methods 

    private function buildPageVM(string $pageName): PageElementViewModel
    {
        $sections = $this->pageElementService->getPageSections($pageName);
        return new PageElementViewModel($sections);
    }

}