<?php

namespace App\Controllers;

use App\Services\Interfaces\IUserService;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\ITicketService;
use App\Models\UserModel;
use App\Framework\Controller;
use InvalidArgumentException;
use Exception;

class UserController extends Controller
{
    private IUserService $userService;
    private IAuthService $authService;
    private ITicketService $ticketService;

    public function __construct(IUserService $userService, IAuthService $authService, ITicketService $ticketService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
        $this->ticketService = $ticketService;
    }

    public function index($vars = [])
    {
        //Forbiden login for non-admins even if they type /admin/users in URL
        $this->requireAdmin();

        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $sort = $_GET['sort'] ?? 'created_at_desc';

        $users = $this->userService->getFilteredUsers($search, $role, $sort);

        require_once __DIR__ . '/../Views/admin/users/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // mapping  POST to user model (ID 0 for new user)
                $user = new UserModel(
                    0,
                    strtolower(trim($_POST['email'] ?? '')),
                        '', // password is handled by service
                    trim($_POST['userName'] ?? ''),
                    trim($_POST['fullName'] ?? ''),
                    trim($_POST['phoneNumber'] ?? ''),
                    $_POST['role'] ?? 'User',
                    date('Y-m-d H:i:s'),
                    null,
                    null, // Picture handled by Service
                    null
                );

                $this->userService->createUser( $user, $_POST['password'] ?? '', $_FILES['profilePicture'] ?? null);

                $_SESSION['flash_success'] = "User '{$user->getFullName()}' created successfully!";

                header('Location: /admin/users');
                exit;

            } catch (InvalidArgumentException $e) {
                // this catches validation errors from the service
                $_SESSION['error'] = $e->getMessage();
            } catch (Exception $e) {
                // this catches system/database errors
                error_log($e->getMessage());
                $_SESSION['error'] = "A system error occurred.";
            } 
        }

        require_once __DIR__ . '/../Views/admin/users/create.php';
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        try {
            $user = $this->userService->getUserById($id);
            if (!$user) {
                $_SESSION['error'] = "User not found.";
                header('Location: /admin/users');
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user->setEmail(strtolower(trim($_POST['email'])));
                $user->setFullName(trim($_POST['fullName']));
                $user->setUserName(trim($_POST['userName']));
                $user->setPhoneNumber(trim($_POST['phoneNumber']));
                $user->setRole($_POST['role']);

                $this->userService->updateUser($user, $_FILES['profilePicture'] ?? null);
                
                $_SESSION['flash_success'] = "Changes saved successfully!";
                header('Location: /admin/users');
                exit;
            }
        } catch (InvalidArgumentException $e) {
            $_SESSION['error'] = $e->getMessage();
        } catch (Exception $e) {
            error_log("User Edit Error: " . $e->getMessage());
            $_SESSION['error'] = "Failed to update user.";
        }

        require_once __DIR__ . '/../Views/admin/users/edit.php';
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = (int)($_POST['id'] ?? 0);
                $this->userService->deleteUser($id);
                $_SESSION['flash_success'] = "User is removed successfully!";
            } catch (Exception $e) {
                error_log("User Delete Error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to delete user.";
            }
        }
        header('Location: /admin/users');
        exit;
    }

    public function restore()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = (int)($_POST['id'] ?? 0);
                $this->userService->restoreUser($id);
                $_SESSION['flash_success'] = "User is restored successfully!";
            } catch (Exception $e) {
                error_log("User Restore Error: " . $e->getMessage());
                $_SESSION['error'] = "Failed to restore user.";
            }
        }
        header('Location: /admin/users');
        exit;
    }

    public function profile()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
        
        $sessionUser = $this->getCurrentUser();
        $id = (int)$sessionUser['id'];
        $user = $this->userService->getUserById($id);

        if (!$user) {
            $this->redirect('/');
        }

        $tickets = $this->ticketService->getUserTickets($id);
        $tickets = $this->ticketService->hydrateTickets($tickets);

        require_once __DIR__ . '/../Views/profile/show.php';
    }

    public function editProfile()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }

        $sessionUser = $this->getCurrentUser();
        $user = $this->userService->getUserById((int)$sessionUser['id']);

        if (!$user) {
            $this->redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->userService->updateProfile($user, $_POST, $_FILES['profilePicture'] ?? null);

                // syncing session with updated Model data
                $_SESSION['user']['email'] = $user->getEmail();
                $_SESSION['user']['userName'] = $user->getUserName();

                $_SESSION['flash_success'] = 'Profile updated successfully!';
                $this->redirect('/profile');
                
            } catch (\InvalidArgumentException $e) {
                // catching errors from service 
                $_SESSION['error'] = $e->getMessage();
            } catch (\Exception $e) {
                // catching unexpected system/DB errors
                error_log("Profile Update Error: " . $e->getMessage());
                $_SESSION['error'] = "A system error occurred.";
            }
        }

        require_once __DIR__ . '/../Views/profile/edit.php';
    }

    public function deleteSelf()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_SESSION['user']['id'] ?? 0);

            if ($id > 0) {
                $this->userService->deleteUser($id);
            }

            $_SESSION['flash_success'] = "Your account is deleted successfully!";
            session_destroy();
            header('Location: /');
            exit;
        }

        header('Location: /profile');
        exit;
    }
}
