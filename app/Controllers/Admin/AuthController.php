<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use Database;
use Session;

class AuthController extends BaseController
{
    private Database $db;

    public function __construct(Environment $twig, Database $db)
    {
        parent::__construct($twig);
        $this->db = $db;
    }

    public function login(): void
    {
        Session::checkLogin(); // Redirects to admin index if already logged in
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($username === '' || $password === '') {
                $error = 'Username or Password must not be empty!';
            } else {
                $usernameEsc = $this->db->escape($username);
                $query       = "SELECT * FROM users WHERE username = '$usernameEsc' LIMIT 1";
                $result      = $this->db->select($query);

                if ($result && $result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        Session::set('login', true);
                        Session::set('username', $user['username']);
                        Session::set('userId', $user['id']);
                        Session::set('userRole', $user['role']);
                        header('Location: index.php');
                        exit();
                    } else {
                        $error = 'Username or Password not matched!';
                    }
                } else {
                    $error = 'Username or Password not found!';
                }
            }
        }

        $this->render('dashboard/login.twig', [
            'error' => $error
        ]);
    }

    public function forgotPassword(): void
    {
        Session::checkLogin(); // Redirects to admin index if already logged in

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $email = trim($_POST['email'] ?? '');

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Invalid email address.';
                } else {
                    $emailEsc = $this->db->escape($email);
                    $query    = "SELECT * FROM users WHERE email = '$emailEsc' LIMIT 1";
                    $result   = $this->db->select($query);

                    if ($result && $result->num_rows > 0) {
                        $user     = $result->fetch_assoc();
                        $userId   = (int) $user['id'];
                        $username = $user['username'];

                        // Generate temporary random password
                        $prefix     = substr($email, 0, 3);
                        $randDigit  = random_int(10000, 99999);
                        $newPass    = $prefix . $randDigit;
                        $newHash    = password_hash($newPass, PASSWORD_BCRYPT);
                        $newHashEsc = $this->db->escape($newHash);

                        $updated = $this->db->update("UPDATE users SET password = '$newHashEsc' WHERE id = $userId");

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
        }

        $this->render('dashboard/forget_pass.twig', [
            'title'     => TITLE,
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'email'     => $_POST['email'] ?? ''
        ]);
    }
}
