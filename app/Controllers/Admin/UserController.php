<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\UserRepositoryInterface;
use App\Security\InputValidator;
use Session;
use Twig\Environment;

class UserController extends BaseController
{
    private UserRepositoryInterface $userModel;

    public function __construct(Environment $twig, UserRepositoryInterface $userModel)
    {
        parent::__construct($twig);
        $this->userModel = $userModel;
    }

    public function list(): void
    {
        $this->requireLogin();

        $error   = '';
        $success = '';

        $delId = $this->getIntParam('delUser');
        if ($delId > 0) {
            if (Session::get('userRole') !== '0') {
                $error = 'You do not have permission to delete users.';
            } else {
                $deleted = $this->userModel->delete($delId);
                if ($deleted) {
                    $success = 'User deleted successfully.';
                } else {
                    $error = 'User not deleted.';
                }
            }
        }

        $this->render('dashboard/user_list.twig', [
            'error'   => $error,
            'success' => $success,
            'users'   => $this->userModel->getAll()
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $error     = '';
        $success   = '';
        $username  = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $this->render('dashboard/add_user.twig', [
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken(),
                    'username'  => $username
                ]);
                return;
            }

            $username = $this->getRequestBody('username');
            $password = $this->getRequestBody('password');
            $role     = $this->getRequestBody('role');

            $validator = new InputValidator();
            $validator
                ->required('username', $username, 'Username')
                ->required('password', $password, 'Password')
                ->minLength('password', $password, 6, 'Password')
                ->required('role', $role, 'Role')
                ->inArray('role', $role, ['0', '1', '2'], 'Role');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } elseif ($this->userModel->usernameExists($username)) {
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

        $userId = $this->getIntParam('user_id');
        if ($userId <= 0) {
            $this->redirect('user_list.php');
        }

        $userData = $this->userModel->getById($userId);
        if (!$userData) {
            $this->redirect('user_list.php');
        }

        $this->render('dashboard/views_user.twig', [
            'userData' => $userData
        ]);
    }
}
