<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can reply to messages
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['msg_id']) || (int) $_GET['msg_id'] <= 0) {
    header('Location: inbox.php');
    exit();
}

$id = (int) $_GET['msg_id'];

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection Check
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!Session::checkCsrfToken($csrfToken)) {
        $error = 'Security check failed. Please refresh the page.';
    } else {
        $to       = trim($_POST['toEmail']  ?? '');
        $from     = trim($_POST['frmEmail'] ?? '');
        $subject  = trim($_POST['subj']     ?? '');
        $msg      = trim($_POST['msg']      ?? '');

        if (empty($to) || empty($from) || empty($subject) || empty($msg)) {
            $error = 'All fields are required.';
        } elseif (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid sender email address.';
        } else {
            $headers = "From: $from\r\n";
            $headers .= "Reply-To: $from\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            $sendmail = mail($to, $subject, $msg, $headers);
            if ($sendmail) {
                $success = 'Message sent successfully.';
            } else {
                $error = 'Something went wrong. Could not send mail.';
            }
        }
    }
}

$result = $contactModel->getById($id);
if (!$result) {
    header('Location: inbox.php');
    exit();
}
echo $twig->render('dashboard/reply_msg.twig', [
    'error'      => $error,
    'success'    => $success,
    'csrfToken'  => Session::getCsrfToken(),
    'msg_result' => $result
]);