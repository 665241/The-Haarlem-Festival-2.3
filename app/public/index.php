<?php
require __DIR__ . '/../../vendor/autoload.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ini_set('display_errors', '0'); // hide from browser
// ini_set('log_errors', '1');     // log instead

use FastRoute\RouteCollector;
use App\Models\Enums\EventTypeEnum;
use App\Repositories\TicketRepository;

use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    //$r->addRoute('GET', '/', ['App\Controllers\HomeController', 'home']);
    $r->addRoute('GET', '/', ['App\Controllers\AuthController', 'index']);

    $r->addRoute('GET', '/login', ['App\Controllers\AuthController', 'showLoginForm']);
    $r->addRoute('POST', '/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/logout', ['App\Controllers\AuthController', 'logout']);

    // Admin User Management
    $r->addRoute('GET', '/admin/users', ['App\Controllers\UserController', 'index']);
    $r->addRoute('POST', '/admin/users/delete', ['App\Controllers\UserController', 'delete']);
    $r->addRoute('GET', '/admin/users/create', ['App\Controllers\UserController', 'create']);
    $r->addRoute('POST', '/admin/users/create', ['App\Controllers\UserController', 'create']);
    $r->addRoute('GET', '/admin/users/edit', ['App\Controllers\UserController', 'edit']);
    $r->addRoute('POST', '/admin/users/edit', ['App\Controllers\UserController', 'edit']);
    $r->addRoute('POST', '/admin/users/restore', ['App\Controllers\UserController', 'restore']);

    // Admin Restaurant Management
    $r->addRoute('GET', '/admin/yummy', ['App\Controllers\RestaurantController', 'adminIndex']);
    $r->addRoute('GET', '/admin/yummy/create', ['App\Controllers\RestaurantController', 'showCreateForm']);
    $r->addRoute('POST', '/admin/yummy/create', ['App\Controllers\RestaurantController', 'store']);
    $r->addRoute('GET', '/admin/yummy/edit/{id:\d+}', ['App\Controllers\RestaurantController', 'showEditForm']);
    $r->addRoute('POST', '/admin/yummy/edit/{id:\d+}', ['App\Controllers\RestaurantController', 'update']);
    $r->addRoute('GET', '/admin/yummy/delete/{id:\d+}', ['App\Controllers\RestaurantController', 'delete']);
    $r->addRoute('POST', '/admin/yummy/sessions/add', ['App\Controllers\RestaurantController', 'addSessions']);
    $r->addRoute('POST', '/admin/yummy/sessions/delete', ['App\Controllers\RestaurantController', 'deleteSession']);
    $r->addRoute('POST', '/admin/yummy/sessions/update', ['App\Controllers\RestaurantController', 'updateSession']);

    // Admin Jazz Artist Management
    $r->addRoute('GET', '/admin/jazz', ['App\Controllers\JazzController', 'adminIndex']);
    $r->addRoute('GET', '/admin/jazz/create', ['App\Controllers\JazzController', 'showCreateForm']);
    $r->addRoute('POST', '/admin/jazz/create', ['App\Controllers\JazzController', 'store']);
    $r->addRoute('GET', '/admin/jazz/edit/{id:\d+}', ['App\Controllers\JazzController', 'showEditForm']);
    $r->addRoute('POST', '/admin/jazz/edit/{id:\d+}', ['App\Controllers\JazzController', 'update']);
    $r->addRoute('GET', '/admin/jazz/delete/{id:\d+}', ['App\Controllers\JazzController', 'delete']);

    // Admin Jazz Event Management
    $r->addRoute('GET', '/admin/jazz/events/create', ['App\Controllers\JazzController', 'showCreateEventForm']);
    $r->addRoute('POST', '/admin/jazz/events/create', ['App\Controllers\JazzController', 'storeEvent']);
    $r->addRoute('GET', '/admin/jazz/events/edit/{id:\d+}', ['App\Controllers\JazzController', 'showEditEventForm']);
    $r->addRoute('POST', '/admin/jazz/events/edit/{id:\d+}', ['App\Controllers\JazzController', 'updateEvent']);
    $r->addRoute('GET', '/admin/jazz/events/delete/{id:\d+}', ['App\Controllers\JazzController', 'deleteEvent']);

    // Admin Jazz Pass Management
    $r->addRoute('GET', '/admin/jazz/passes/create', ['App\Controllers\JazzController', 'showCreatePassForm']);
    $r->addRoute('POST', '/admin/jazz/passes/create', ['App\Controllers\JazzController', 'storePass']);
    $r->addRoute('GET', '/admin/jazz/passes/edit/{id:\d+}', ['App\Controllers\JazzController', 'showEditPassForm']);
    $r->addRoute('POST', '/admin/jazz/passes/edit/{id:\d+}', ['App\Controllers\JazzController', 'updatePass']);
    $r->addRoute('GET', '/admin/jazz/passes/delete/{id:\d+}', ['App\Controllers\JazzController', 'deletePass']);

    // Public Jazz routes
    $r->addRoute('GET', '/jazz', ['App\Controllers\JazzController', 'index']);
    $r->addRoute('GET', '/jazz/{id:\d+}', ['App\Controllers\JazzController', 'detail']);

    // Admin Chef Management
    $r->addRoute('GET', '/admin/chefs/create', ['App\Controllers\ChefController', 'showCreateForm']);
    $r->addRoute('POST', '/admin/chefs/create', ['App\Controllers\ChefController', 'store']);
    $r->addRoute('GET', '/admin/chefs/edit/{id:\d+}', ['App\Controllers\ChefController', 'showEditForm']);
    $r->addRoute('POST', '/admin/chefs/edit/{id:\d+}', ['App\Controllers\ChefController', 'update']);
    $r->addRoute('GET', '/admin/chefs/delete/{id:\d+}', ['App\Controllers\ChefController', 'delete']);

    $r->addRoute('GET', '/register', ['App\Controllers\AuthController', 'showRegisterForm']);
    $r->addRoute('POST', '/register', ['App\Controllers\AuthController', 'register']);


    // Ticket and Personal Program + Kids Event routes
    $r->addRoute('POST',  '/addTicket', ['App\Controllers\TicketController', 'addTicket']);
    $r->addRoute('POST', '/removeTicket', ['App\Controllers\TicketController', 'removeTicket']);
    $r->addRoute('GET',  '/kidsEvent', ['App\Controllers\KidsEventController', 'index']);
    $r->addRoute('GET',  '/personalProgram', ['App\Controllers\TicketController', 'index']);
    $r->addRoute('GET',  '/admin/kidsPage', ['App\Controllers\KidsEventController', 'adminIndex']);
    $r->addRoute('GET',  '/admin/elements/edit/{id:\d+}', ['App\Controllers\PageElementController', 'showEditForm']);
    $r->addRoute('POST',  '/admin/elements/edit/{id:\d+}', ['App\Controllers\PageElementController', 'saveTextChanges']);
    $r->addRoute('GET',  '/admin/elements/editImg/{id:\d+}', ['App\Controllers\PageElementController', 'showImgEditForm']);
    $r->addRoute('POST',  '/admin/elements/editImg/{id:\d+}', ['App\Controllers\PageElementController', 'saveImgChanges']);
    $r->addRoute('GET',  '/admin/kids-events/edit/{id:\d+}', ['App\Controllers\KidsEventController', 'edit']);
    $r->addRoute('GET',  '/admin/kids-events/create', ['App\Controllers\KidsEventController', 'create']);
    $r->addRoute('POST',  '/admin/kids-events/save', ['App\Controllers\KidsEventController', 'save']);
    $r->addRoute('POST', '/admin/kids-events/delete', ['App\Controllers\KidsEventController', 'delete']);
    $r->addRoute('GET',  '/admin/home/index', ['App\Controllers\HomeController', 'adminIndex']);
    $r->addRoute('GET',  '/admin/dashboard', ['App\Controllers\TicketController', 'adminIndex']);
    $r->addRoute('GET',  '/extrakids/{id:\d+}', ['App\Controllers\KidsEventController', 'detail']);
    $r->addRoute('GET', '/admin/extrakids/create', ['App\Controllers\KidsEventController', 'createExtra']);
    $r->addRoute('POST', '/admin/extrakids/save', ['App\Controllers\KidsEventController', 'storeExtra']);
    $r->addRoute('GET', '/admin/extrakids/edit/{id:\d+}', ['App\Controllers\KidsEventController', 'editExtra']);
    $r->addRoute('POST', '/admin/extrakids/delete', ['App\Controllers\KidsEventController', 'deleteExtra']);
    $r->addRoute('GET',  '/admin/elements/createForm', ['App\Controllers\PageElementController', 'createForm']);
    $r->addRoute('POST',  '/admin/elements/store', ['App\Controllers\PageElementController', 'store']);

    $r->addRoute('POST',  '/admin/export-csv', ['App\Controllers\TicketController', 'exportCsv']);

    // Yummy / Restaurant Routes
    $r->addRoute('GET', '/yummy', ['App\Controllers\RestaurantController', 'index']);
    $r->addRoute('GET', '/yummy/restaurant/{id:\d+}', ['App\Controllers\RestaurantController', 'showDetails']);

    // User Profile Routes
    $r->addRoute('GET',  '/profile',       ['App\Controllers\UserController', 'profile']);
    $r->addRoute('GET',  '/profile/edit',  ['App\Controllers\UserController', 'editProfile']);
    $r->addRoute('POST', '/profile/edit',  ['App\Controllers\UserController', 'editProfile']); // same method handles POST like admin edit()
    $r->addRoute('POST', '/profile/delete', ['App\Controllers\UserController', 'deleteSelf']);
    // History routes
    $r->addRoute('GET', '/history', ['App\Controllers\HistoryController', 'index']);
    $r->addRoute('GET', '/history/booking', ['App\Controllers\HistoryController', 'booking']);
    $r->addRoute('POST', '/history/book', ['App\Controllers\HistoryController', 'book']);

    $r->addRoute('GET', '/admin/history/venues', ['App\Controllers\HistoryController', 'adminVenues']);
    $r->addRoute('GET', '/admin/history/venues/create', ['App\Controllers\HistoryController', 'createVenue']);
    $r->addRoute('POST', '/admin/history/venues/create', ['App\Controllers\HistoryController', 'storeVenue']);
    $r->addRoute('GET', '/admin/history/venues/edit', ['App\Controllers\HistoryController', 'editVenue']);
    $r->addRoute('POST', '/admin/history/venues/edit', ['App\Controllers\HistoryController', 'updateVenue']);
    $r->addRoute('POST', '/admin/history/venues/delete', ['App\Controllers\HistoryController', 'deleteVenue']);


    // Public Dance routes
    $r->addRoute('GET', '/dance', ['App\Controllers\DanceController', 'index']);
    $r->addRoute('GET', '/dance/{id:\d+}', ['App\Controllers\DanceController', 'detail']);

    // Admin Dance Artist Management
    $r->addRoute('GET', '/admin/dance', ['App\Controllers\DanceController', 'adminIndex']);
    $r->addRoute('GET', '/admin/dance/create', ['App\Controllers\DanceController', 'showCreateForm']);
    $r->addRoute('POST', '/admin/dance/create', ['App\Controllers\DanceController', 'store']);
    $r->addRoute('GET', '/admin/dance/edit/{id:\d+}', ['App\Controllers\DanceController', 'showEditForm']);
    $r->addRoute('POST', '/admin/dance/edit/{id:\d+}', ['App\Controllers\DanceController', 'update']);
    $r->addRoute('GET', '/admin/dance/delete/{id:\d+}', ['App\Controllers\DanceController', 'delete']);

    // Admin Dance Event Management
    $r->addRoute('GET', '/admin/dance/events/create', ['App\Controllers\DanceController', 'showCreateEventForm']);
    $r->addRoute('POST', '/admin/dance/events/create', ['App\Controllers\DanceController', 'storeEvent']);
    $r->addRoute('GET', '/admin/dance/events/edit/{id:\d+}', ['App\Controllers\DanceController', 'showEditEventForm']);
    $r->addRoute('POST', '/admin/dance/events/edit/{id:\d+}', ['App\Controllers\DanceController', 'updateEvent']);
    $r->addRoute('GET', '/admin/dance/events/delete/{id:\d+}', ['App\Controllers\DanceController', 'deleteEvent']);

    $r->addRoute('GET', '/admin/history/tours', ['App\Controllers\HistoryController', 'adminTours']);
    $r->addRoute('GET', '/admin/history/tours/create', ['App\Controllers\HistoryController', 'createTour']);
    $r->addRoute('POST', '/admin/history/tours/create', ['App\Controllers\HistoryController', 'storeTour']);
    $r->addRoute('GET', '/admin/history/tours/edit', ['App\Controllers\HistoryController', 'editTour']);
    $r->addRoute('POST', '/admin/history/tours/edit', ['App\Controllers\HistoryController', 'updateTour']);
    $r->addRoute('POST', '/admin/history/tours/delete', ['App\Controllers\HistoryController', 'deleteTour']);

    $r->addRoute('GET', '/history/{id:\d+}', ['App\Controllers\HistoryController', 'detail']);
    // Checkout routes
    $r->addRoute('POST', '/checkout', ['App\Controllers\PaymentController', 'checkout']);
    $r->addRoute('GET', '/payment-success', ['App\Controllers\PaymentController', 'paymentSuccess']);
    $r->addRoute('GET',  '/payment-failed', ['App\Controllers\PaymentController', 'paymentFailed']);
    $r->addRoute('GET',  '/repay', ['App\Controllers\PaymentController', 'repay']);

    //password reset
    $r->addRoute('GET', '/forgetPassword', ['App\Controllers\AuthController', 'showForgetPassword']);
    $r->addRoute('POST', '/forgetPassword', ['App\Controllers\AuthController', 'sendResetLink']);
    $r->addRoute('GET', '/resetPassword', ['App\Controllers\AuthController', 'showResetPassword']);
    $r->addRoute('POST', '/resetPassword', ['App\Controllers\AuthController', 'resetPassword']);
    

    // QR/employee scanning routes
    $r->addRoute('GET', '/qr', ['App\Controllers\QrController', 'index']);
    $r->addRoute('GET', '/scan', ['App\Controllers\TicketController', 'scan']);
    $r->addRoute('GET', '/employee/scan', ['App\Controllers\TicketController', 'scanPage']);
});

/**
 * Get the request method and URI from the server variables and invoke the dispatcher.
 */
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

/**
 * Switch on the dispatcher result and call the appropriate controller method if found.
 */
switch ($routeInfo[0]) {
    // Handle not found routes
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;

    // Handle routes that were invoked with the wrong HTTP method
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;

    // Handle found routes
    case FastRoute\Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $vars = $routeInfo[2];

        // Protect all /admin routes
        if (str_starts_with($uri, '/admin')) {
            if (empty($_SESSION['user'])) {
                header('Location: /login');
                exit;
            }

            if (($_SESSION['user']['role'] ?? '') !== 'Admin') {
                header('Location: /');
                exit;
            }
        }

        if ($class === 'App\Controllers\UserController') {
            $repository = new \App\Repositories\UserRepository();
            $authService = new \App\Services\AuthService($repository);
            $ticketRepo = new \App\Repositories\TicketRepository();

            $restaurantRepo = new \App\Repositories\Yummy\RestaurantRepository();
            $restaurantService = new \App\Services\Yummy\RestaurantService($restaurantRepo);
            $restaurantSessionRepo = new \App\Repositories\Yummy\RestaurantSessionRepository();
            $restaurantSessionService = new \App\Services\Yummy\RestaurantSessionService($restaurantSessionRepo, $restaurantRepo);

            $artistRepository = new \App\Repositories\ArtistRepository();
            $artistService = new \App\Services\ArtistService($artistRepository);
            $jazzEventRepository = new \App\Repositories\JazzEventRepository();
            $jazzEventService = new \App\Services\JazzEventService($jazzEventRepository);
            $jazzPassRepository = new \App\Repositories\JazzPassRepository();
            $jazzPassService = new \App\Services\JazzPassService($jazzPassRepository);

            $historyVenueRepository = new \App\Repositories\HistoryVenueRepository();
            $historyEventRepository = new \App\Repositories\HistoryEventRepository();
            $historyService = new \App\Services\HistoryService($historyEventRepository, $historyVenueRepository);

            $kidsEventRepo = new \App\Repositories\KidsEventRepository();
            $kidsEventService = new \App\Services\KidsEventService($kidsEventRepo);

          
            $eventRepo = new App\Repositories\EventRepository();
            $eventService = new App\Services\EventService($eventRepo);
            $personalProgramService = new \App\Services\PersonalProgramService($eventRepo, $repository);
            
            $ticketService = new \App\Services\TicketService($ticketRepo, $restaurantSessionService, $restaurantService, $jazzEventService, $historyService, $kidsEventService, $historyVenueRepository, $artistService, $jazzPassService, $eventService, $personalProgramService);
            $service = new \App\Services\UserService($repository, $authService);
            $controller = new $class($service, $authService, $ticketService);
        } elseif ($class === 'App\Controllers\RestaurantController') {
            $pageElementService = new \App\Services\PageElementService(new \App\Repositories\PageElementRepository());
            $repository = new \App\Repositories\Yummy\RestaurantRepository();
            $chefRepo = new \App\Repositories\Yummy\ChefRepository();
            $sessionRepo = new \App\Repositories\Yummy\RestaurantSessionRepository();
            $service = new \App\Services\Yummy\RestaurantService($repository);
            $chefService = new \App\Services\Yummy\ChefService($chefRepo);
            $sessionService = new \App\Services\Yummy\RestaurantSessionService($sessionRepo, $repository);
            $controller = new $class($service, $chefService, $sessionService, $pageElementService);
        } elseif ($class === 'App\Controllers\JazzController') {
            $artistRepository = new \App\Repositories\ArtistRepository();
            $artistService = new \App\Services\ArtistService($artistRepository);
            $jazzEventRepository = new \App\Repositories\JazzEventRepository();
            $jazzEventService = new \App\Services\JazzEventService($jazzEventRepository);
            $jazzPassService = new \App\Services\JazzPassService(new \App\Repositories\JazzPassRepository());
            $controller = new $class($artistService, $jazzEventService, $jazzPassService);
        } elseif ($class === 'App\Controllers\ChefController') {
            $chefRepo = new \App\Repositories\Yummy\ChefRepository();
            $chefService = new \App\Services\Yummy\ChefService($chefRepo);
            $controller = new $class($chefService);
        } elseif ($class === 'App\Controllers\TicketController') {
            $restaurantRepo = new \App\Repositories\Yummy\RestaurantRepository();
            $restaurantService = new \App\Services\Yummy\RestaurantService($restaurantRepo);
            $restaurantSessionRepo = new \App\Repositories\Yummy\RestaurantSessionRepository();
            $restaurantSessionService = new \App\Services\Yummy\RestaurantSessionService($restaurantSessionRepo, $restaurantRepo);

            $artistRepository = new \App\Repositories\ArtistRepository();
            $artistService = new \App\Services\ArtistService($artistRepository);
            $jazzEventRepository = new \App\Repositories\JazzEventRepository();
            $jazzEventService = new \App\Services\JazzEventService($jazzEventRepository);
            $jazzPassRepository = new \App\Repositories\JazzPassRepository();
            $jazzPassService = new \App\Services\JazzPassService($jazzPassRepository);

            $historyVenueRepository = new \App\Repositories\HistoryVenueRepository();
            $historyEventRepository = new \App\Repositories\HistoryEventRepository();
            $historyService = new \App\Services\HistoryService($historyEventRepository, $historyVenueRepository);

            $userRepo = new App\Repositories\UserRepository();
            $eventRepo = new App\Repositories\EventRepository();
            $eventService = new App\Services\EventService($eventRepo);
            $ticketRepo =  new \App\Repositories\TicketRepository();
            $personalProgramService = new \App\Services\PersonalProgramService($eventRepo, $userRepo);
            $kidsEventRepo = new \App\Repositories\KidsEventRepository();
            $kidsEventService = new \App\Services\KidsEventService($kidsEventRepo);
            $ticketService = new \App\Services\TicketService($ticketRepo, $restaurantSessionService, $restaurantService, $jazzEventService, $historyService, $kidsEventService, $historyVenueRepository, $artistService, $jazzPassService, $eventService, $personalProgramService);
            $controller = new $class($ticketRepo, $ticketService);
        } elseif ($class === 'App\Controllers\PaymentController') {
            $restaurantRepo = new \App\Repositories\Yummy\RestaurantRepository();
            $restaurantService = new \App\Services\Yummy\RestaurantService($restaurantRepo);
            $restaurantSessionRepo = new \App\Repositories\Yummy\RestaurantSessionRepository();
            $restaurantSessionService = new \App\Services\Yummy\RestaurantSessionService($restaurantSessionRepo, $restaurantRepo);
            $eventRepo = new \App\Repositories\EventRepository();
            $userRepo = new \App\Repositories\UserRepository();

            $communicationService = new \App\Services\CommunicationService();
            $ticketRepo =  new \App\Repositories\TicketRepository();
            $personalProgramService = new \App\Services\PersonalProgramService($eventRepo, $userRepo);

            $jazzEventRepository = new \App\Repositories\JazzEventRepository();
            $jazzEventService = new \App\Services\JazzEventService($jazzEventRepository);
            $jazzPassRepository = new \App\Repositories\JazzPassRepository();
            $jazzPassService = new \App\Services\JazzPassService($jazzPassRepository);

            $authService = new \App\Services\AuthService($userRepo); 
            $userService = new \App\Services\UserService($userRepo, $authService);

            $paymentService = new \App\Services\PaymentService($ticketRepo, $restaurantSessionService, $jazzEventService, $jazzPassService, $userRepo, $eventRepo);

            $controller = new $class($paymentService, $communicationService, $userService);
        } elseif ($class === 'App\Controllers\HistoryController') {
            $historyVenueRepository = new \App\Repositories\HistoryVenueRepository();
            $historyEventRepository = new \App\Repositories\HistoryEventRepository();
            $historyService = new \App\Services\HistoryService($historyEventRepository, $historyVenueRepository);

            $userRepo = new \App\Repositories\UserRepository();
            $eventRepo = new \App\Repositories\EventRepository();
            $personalProgramService = new \App\Services\PersonalProgramService($eventRepo, $userRepo);

            $controller = new $class($historyService, $personalProgramService);
        } else {
            $controller = new $class();
        }

        echo $controller->$method($vars);
        break;
}
