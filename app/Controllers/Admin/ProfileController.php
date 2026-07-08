<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\UserRepositoryInterface;
use App\Security\InputValidator;
use Session;
use Twig\Environment;

class ProfileController extends BaseController
{
    private UserRepositoryInterface $userModel;

    public function __construct(Environment $twig, UserRepositoryInterface $userModel)
    {
        parent::__construct($twig);
        $this->userModel = $userModel;
    }

    public function index(): void
    {
        $this->requireLogin();

        $userId = (int) Session::get('userId');
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $userData = $this->userModel->getById($userId);
                $this->render('dashboard/profile.twig', [
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken(),
                    'userData'  => $userData
                ]);
                return;
            }

            $name    = $this->getRequestBody('name');
            $email   = $this->getRequestBody('email');
            $details = $this->getRequestBody('details');

            $validator = new InputValidator();
            $validator
                ->required('name', $name, 'Name')
                ->required('email', $email, 'Email')
                ->email('email', $email)
                ->required('details', $details, 'Details');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $updated = $this->userModel->update($userId, [
                    'name'     => $name,
                    'username' => Session::get('username'),
                    'email'    => $email,
                    'details'  => $details
                ]);

                if ($updated) {
                    $success = 'Profile Updated Successfully.';
                } else {
                    $error = 'Profile Not Updated!';
                }
            }
        }

        $userData = $this->userModel->getById($userId);
        if (!$userData) {
            $this->redirect('index.php');
        }

        $this->render('dashboard/profile.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'userData'  => $userData
        ]);
    }

    public function changePassword(): void
    {
        $this->requireLogin();

        $userId = (int) Session::get('userId');
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $this->render('dashboard/change_password.twig', [
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken()
                ]);
                return;
            }

            $oldPass = $_POST['old_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';

            $validator = new InputValidator();
            $validator
                ->required('old_password', $oldPass, 'Old password')
                ->required('new_password', $newPass, 'New password')
                ->minLength('new_password', $newPass, 6, 'New password');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $user = $this->userModel->getById($userId);
                if ($user && password_verify($oldPass, $user['password'])) {
                    $newHash = password_hash($newPass, PASSWORD_BCRYPT);
                    $updated = $this->userModel->updatePassword($userId, $newHash);
                    if ($updated) {
                        $success = 'Password Updated Successfully.';
                    } else {
                        $error = 'Password Not Updated!';
                    }
                } else {
                    $error = 'Old Password does not match!';
                }
            }
        }

        $this->render('dashboard/change_password.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken()
        ]);
    }
}
