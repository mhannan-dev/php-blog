<?php 
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkLogin();
?>


        <?php 
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF Protection Check
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $email = trim($_POST['email'] ?? '');

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Invalid email address.';
                } else {
                    $emailEsc = $db->escape($email);
                    $query    = "SELECT * FROM users WHERE email = '$emailEsc' LIMIT 1";
                    $result   = $db->select($query);

                    if ($result) {
                        $user     = $result->fetch_assoc();
                        $userId   = (int) $user['id'];
                        $username = $user['username'];

                        // Generate temporary random password
                        $prefix    = substr($email, 0, 3);
                        $randDigit = random_int(10000, 99999);
                        $newPass   = $prefix . $randDigit;
                        $newHash   = password_hash($newPass, PASSWORD_BCRYPT);
                        $newHashEsc = $db->escape($newHash);

                        $updated = $db->update("UPDATE users SET password = '$newHashEsc' WHERE id = $userId");

                        if ($updated) {
                            $to      = $email;
                            $from    = "admin@example.com";
                            $subject = "Your New Password";
                            $message = "Hello, $username. Your new temporary password is: $newPass\r\n\r\nPlease log in and change it immediately.";
                            
                            $headers = "From: $from\r\n";
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
echo $twig->render('dashboard/forget_pass.twig', [
    'title'     => TITLE,
    'error'     => $error,
    'success'   => $success,
    'csrfToken' => Session::getCsrfToken(),
    'email'     => $_POST['email'] ?? ''
]);