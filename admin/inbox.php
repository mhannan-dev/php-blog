<?php
require_once __DIR__ . '/../app/bootstrap.php';
Session::checkSession();

// Only admins (role = '0') can manage inbox
if (Session::get('userRole') !== '0') {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';

// Handle mark as seen
if (isset($_GET['seen_id'])) {
    $seenId = (int) $_GET['seen_id'];
    if ($seenId > 0) {
        $updated = $contactModel->markAsSeen($seenId);
        if ($updated) {
            $success = 'Message sent to seen box.';
        } else {
            $error = 'Something went wrong.';
        }
    }
}

// Handle delete message
if (isset($_GET['del_msg'])) {
    $delId = (int) $_GET['del_msg'];
    if ($delId > 0) {
        $deleted = $contactModel->delete($delId);
        if ($deleted) {
            $success = 'Message deleted successfully.';
        } else {
            $error = 'Message not deleted successfully.';
        }
    }
}
?>

$unreadContactsResult = $contactModel->getUnread();
$unreadContacts = [];
if ($unreadContactsResult && $unreadContactsResult->num_rows > 0) {
    while ($contact = $unreadContactsResult->fetch_assoc()) {
        $unreadContacts[] = $contact;
    }
}

$seenContactsResult = $contactModel->getSeen();
$seenContacts = [];
if ($seenContactsResult && $seenContactsResult->num_rows > 0) {
    while ($contact = $seenContactsResult->fetch_assoc()) {
        $seenContacts[] = $contact;
    }
}

echo $twig->render('dashboard/inbox.twig', [
    'error'           => $error,
    'success'         => $success,
    'unread_contacts' => $unreadContacts,
    'seen_contacts'   => $seenContacts
]);
