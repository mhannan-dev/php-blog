<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use User;
use Session;
use mysqli_result;

class ProfileController extends BaseController
{
    private User $userModel;

    public function __construct(Environment $twig, User $userModel)
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
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $name    = trim($_POST['name']    ?? '');
                $email   = trim($_POST['email']   ?? '');
                $details = trim($_POST['details'] ?? '');

                if ($name === '' || $email === '' || $details === '') {
                    $error = 'Fields must not be empty.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Invalid email format.';
                } else {
                    $updated = $this->userModel->updateProfile($userId, $name, $email, $details);

                    if ($updated) {
                        $success = 'Profile Updated Successfully.';
                    } else {
                        $error = 'Profile Not Updated!';
                    }
                }
            }
        }

        $userData = $this->userModel->getById($userId);
        if (!$userData) {
            header('Location: index.php');
            exit();
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
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $oldPass = $_POST['old_password'] ?? '';
                $newPass = $_POST['new_password'] ?? '';

                if (empty($oldPass) || empty($newPass)) {
                    $error = 'Fields must not be empty.';
                } elseif (strlen($newPass) < 6) {
                    $error = 'New password must be at least 6 characters.';
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
        }

        $this->render('dashboard/change_password.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken()
        ]);
    }
}
