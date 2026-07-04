<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use Contact;
use Session;
use mysqli_result;

class InboxController extends BaseController
{
    private Contact $contactModel;

    public function __construct(Environment $twig, Contact $contactModel)
    {
        parent::__construct($twig);
        $this->contactModel = $contactModel;
    }

    public function index(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if (isset($_GET['seen_id'])) {
            $seenId = (int) $_GET['seen_id'];
            if ($seenId > 0) {
                $updated = $this->contactModel->markAsSeen($seenId);
                if ($updated) {
                    $success = 'Message sent to seen box.';
                } else {
                    $error = 'Something went wrong.';
                }
            }
        }

        if (isset($_GET['del_msg'])) {
            $delId = (int) $_GET['del_msg'];
            if ($delId > 0) {
                $deleted = $this->contactModel->delete($delId);
                if ($deleted) {
                    $success = 'Message deleted successfully.';
                } else {
                    $error = 'Message not deleted successfully.';
                }
            }
        }

        $unreadContactsResult = $this->contactModel->getUnread();
        $unreadContacts = [];
        if ($unreadContactsResult && $unreadContactsResult instanceof mysqli_result) {
            $unreadContacts = $unreadContactsResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $seenContactsResult = $this->contactModel->getSeen();
        $seenContacts = [];
        if ($seenContactsResult && $seenContactsResult instanceof mysqli_result) {
            $seenContacts = $seenContactsResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/inbox.twig', [
            'error'           => $error,
            'success'         => $success,
            'unread_contacts' => $unreadContacts,
            'seen_contacts'   => $seenContacts
        ]);
    }

    public function view(): void
    {
        $this->requireAdmin();

        if (!isset($_GET['msg_id']) || (int) $_GET['msg_id'] <= 0) {
            header('Location: inbox.php');
            exit();
        }

        $msgId = (int) $_GET['msg_id'];
        $msgData = $this->contactModel->getById($msgId);

        if (!$msgData) {
            header('Location: inbox.php');
            exit();
        }

        $this->render('dashboard/view_msg.twig', [
            'msgData' => $msgData
        ]);
    }

    public function reply(): void
    {
        $this->requireAdmin();

        if (!isset($_GET['msg_id']) || (int) $_GET['msg_id'] <= 0) {
            header('Location: inbox.php');
            exit();
        }

        $msgId = (int) $_GET['msg_id'];
        $msgData = $this->contactModel->getById($msgId);

        if (!$msgData) {
            header('Location: inbox.php');
            exit();
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $to      = trim($_POST['to']      ?? '');
                $from    = trim($_POST['from']    ?? '');
                $subject = trim($_POST['subject'] ?? '');
                $message = trim($_POST['message'] ?? '');

                if ($to === '' || $from === '' || $subject === '' || $message === '') {
                    $error = 'All fields are required.';
                } elseif (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Invalid "From" email address.';
                } else {
                    $headers = "From: $from\r\n";
                    $headers .= "Reply-To: $from\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                    $sendMail = mail($to, $subject, $message, $headers);
                    if ($sendMail) {
                        $success = 'Message sent successfully.';
                    } else {
                        $error = 'Failed to send message. Please check server configuration.';
                    }
                }
            }
        }

        $this->render('dashboard/reply_msg.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'msgData'   => $msgData,
            'post_data' => $_POST
        ]);
    }
}
