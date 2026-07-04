<?php
require_once __DIR__ . '/app/bootstrap.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $msg   = trim($_POST['msg']   ?? '');

    if (empty($fname)) {
        $error = 'First name must not be empty.';
    } elseif (empty($lname)) {
        $error = 'Last name must not be empty.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (empty($msg)) {
        $error = 'Message must not be empty.';
    } else {
        $inserted = $contactModel->create($fname, $lname, $email, $msg);

        if ($inserted) {
            $success = 'Your message has been sent successfully.';
            $_POST = [];
        } else {
            $error = 'Failed to send your message. Please try again.';
        }
    }
}

echo $twig->render('frontend/contact_us.twig', [
    'error'     => $error,
    'success'   => $success,
    'post_data' => $_POST
]);