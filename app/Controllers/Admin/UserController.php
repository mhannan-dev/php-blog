<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use User;
use Session;
use mysqli_result;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct(Environment $twig, User $userModel)
    {
        parent::__construct($twig);
        $this->userModel = $userModel;
    }

    public function list(): void
    {
        $this->requireLogin();

        $error   = '';
        $success = '';

        if (isset($_GET['delUser'])) {
            if (Session::get('userRole') !== '0') {
                $error = 'You do not have permission to delete users.';
            } else {
                $delId = (int) $_GET['delUser'];
                if ($delId > 0) {
                    $deleted = $this->userModel->delete($delId);
                    if ($deleted) {
                        $success = 'User deleted successfully.';
                    } else {
                        $error = 'User not deleted.';
                    }
                }
            }
        }

        $usersResult = $this->userModel->getAll();
        $usersArray = [];
        if ($usersResult && $usersResult instanceof mysqli_result) {
            $usersArray = $usersResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/user_list.twig', [
            'error'   => $error,
            'success' => $success,
            'users'   => $usersArray
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';
        $username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $role     = trim($_POST['role']     ?? '');

                if ($username === '' || $password === '' || $role === '') {
                    $error = 'All fields are required.';
                } elseif (strlen($password) < 6) {
                    $error = 'Password must be at least 6 characters.';
                } elseif (!in_array($role, ['0', '1', '2'], true)) {
                    $error = 'Invalid role selected.';
                } else {
                    if ($this->userModel->usernameExists($username)) {
                        $error = 'Username already exists. Please choose a different username.';
                    } else {
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                        $inserted       = $this->userModel->create($username, $hashedPassword, (int) $role);

                        if ($inserted) {
                            $success  = 'User created successfully.';
                            $username = '';
                        } else {
                            $error = 'Failed to create user. Please try again.';
                        }
                    }
                }
            }
        }

        $this->render('dashboard/add_user.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'username'  => $username
        ]);
    }

    public function view(): void
    {
        $this->requireLogin();

        if (!isset($_GET['user_id']) || (int) $_GET['user_id'] <= 0) {
            header('Location: user_list.php');
            exit();
        }

        $userId = (int) $_GET['user_id'];
        $userData = $this->userModel->getById($userId);

        if (!$userData) {
            header('Location: user_list.php');
            exit();
        }

        $this->render('dashboard/views_user.twig', [
            'userData' => $userData
        ]);
    }
}
