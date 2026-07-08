<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\UserRepositoryInterface;
use App\Security\InputValidator;
use Twig\Environment;
use Session;

class AuthController extends BaseController
{
    private UserRepositoryInterface $userModel;

    public function __construct(Environment $twig, UserRepositoryInterface $userModel)
    {
        parent::__construct($twig);
        $this->userModel = $userModel;
    }

    public function login(): void
    {
        Session::checkLogin();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->getRequestBody('username');
            $password = $this->getRequestBody('password');

            $validator = new InputValidator();
            $validator
                ->required('username', $username, 'Username')
                ->required('password', $password, 'Password');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $user = $this->userModel->getByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    Session::regenerate();
                    Session::set('login', true);
                    Session::set('username', $user['username']);
                    Session::set('userId', $user['id']);
                    Session::set('userRole', $user['role']);
                    $this->redirect('index.php');
                } else {
                    $error = 'Username or Password not matched!';
                }
            }
        }

        $this->render('dashboard/login.twig', [
            'error' => $error
        ]);
    }

    public function forgotPassword(): void
    {
        Session::checkLogin();

        $error   = '';
        $success = '';
        $email   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $this->render('dashboard/forget_pass.twig', [
                    'title'     => TITLE,
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken(),
                    'email'     => $email
                ]);
                return;
            }

            $email = $this->getRequestBody('email');

            $validator = new InputValidator();
            $validator
                ->required('email', $email, 'Email')
                ->email('email', $email);

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $user = $this->userModel->getByEmail($email);

                if ($user) {
                    $userId   = (int) $user['id'];
                    $username = $user['username'];

                    $prefix    = substr($email, 0, 3);
                    $randDigit = random_int(10000, 99999);
                    $newPass   = $prefix . $randDigit;
                    $newHash   = password_hash($newPass, PASSWORD_BCRYPT);

                    $updated = (bool) $this->userModel->updatePassword($userId, $newHash);

                    if ($updated) {
                        $to      = $email;
                        $from    = "admin@example.com";
                        $subject = "Your New Password";
                        $message = "Hello, $username. Your new temporary password is: $newPass\r\n\r\nPlease log in and change it immediately.";

                        $headers  = "From: $from\r\n";
                        $headers .= "Reply-To: $from\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                        $sendMail = mail($to, $subject, $message, $headers);
                        if ($sendMail) {
                            $success = 'Please check your email for your new password.';
                        } else {
                            $success = 'Temporary password generated. (Mail could not be sent. New temporary password: ' . $newPass . ')';
                        }
                    } else {
                        $error = 'Failed to generate temporary password. Please try again.';
                    }
                } else {
                    $error = 'No account found with that email address.';
                }
            }
        }

        $this->render('dashboard/forget_pass.twig', [
            'title'     => TITLE,
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'email'     => $email
        ]);
    }
}
