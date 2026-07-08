<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\ContactRepositoryInterface;
use Session;
use Twig\Environment;

class InboxController extends BaseController
{
    private ContactRepositoryInterface $contactModel;

    public function __construct(Environment $twig, ContactRepositoryInterface $contactModel)
    {
        parent::__construct($twig);
        $this->contactModel = $contactModel;
    }

    public function index(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        $seenId = $this->getIntParam('seen_id');
        if ($seenId > 0) {
            $updated = $this->contactModel->markAsSeen($seenId);
            if ($updated) {
                $success = 'Message sent to seen box.';
            } else {
                $error = 'Something went wrong.';
            }
        }

        $delId = $this->getIntParam('del_msg');
        if ($delId > 0) {
            $deleted = $this->contactModel->delete($delId);
            if ($deleted) {
                $success = 'Message deleted successfully.';
            } else {
                $error = 'Message not deleted successfully.';
            }
        }

        $this->render('dashboard/inbox.twig', [
            'error'           => $error,
            'success'         => $success,
            'unread_contacts' => $this->contactModel->getUnread(),
            'seen_contacts'   => $this->contactModel->getSeen()
        ]);
    }

    public function view(): void
    {
        $this->requireAdmin();

        $msgId = $this->getIntParam('msg_id');
        if ($msgId <= 0) {
            $this->redirect('inbox.php');
        }

        $msgData = $this->contactModel->getById($msgId);
        if (!$msgData) {
            $this->redirect('inbox.php');
        }

        $this->render('dashboard/view_msg.twig', [
            'msgData' => $msgData
        ]);
    }

    public function reply(): void
    {
        $this->requireAdmin();

        $msgId = $this->getIntParam('msg_id');
        if ($msgId <= 0) {
            $this->redirect('inbox.php');
        }

        $msgData = $this->contactModel->getById($msgId);
        if (!$msgData) {
            $this->redirect('inbox.php');
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $this->render('dashboard/reply_msg.twig', [
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken(),
                    'msgData'   => $msgData,
                    'post_data' => $_POST
                ]);
                return;
            }

            $to      = $this->getRequestBody('to');
            $from    = $this->getRequestBody('from');
            $subject = $this->getRequestBody('subject');
            $message = $this->getRequestBody('message');

            $validator = new \App\Security\InputValidator();
            $validator
                ->required('to', $to, 'To')
                ->required('from', $from, 'From')
                ->email('from', $from)
                ->required('subject', $subject, 'Subject')
                ->required('message', $message, 'Message');

            if (!$validator->passes()) {
                $error = $validator->firstError();
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

        $this->render('dashboard/reply_msg.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'msgData'   => $msgData,
            'post_data' => $_POST
        ]);
    }
}
