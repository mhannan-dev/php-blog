<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Contracts\ContactRepositoryInterface;
use App\Security\InputValidator;
use Session;
use Twig\Environment;

class ContactController extends BaseController
{
    private ContactRepositoryInterface $contactModel;

    public function __construct(Environment $twig, ContactRepositoryInterface $contactModel)
    {
        parent::__construct($twig);
        $this->contactModel = $contactModel;
    }

    public function index(): void
    {
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $fname = trim($_POST['fname'] ?? '');
                $lname = trim($_POST['lname'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $msg   = trim($_POST['msg']   ?? '');

                $validator = new InputValidator();
                $validator
                    ->required('fname', $fname, 'First name')
                    ->required('lname', $lname, 'Last name')
                    ->required('email', $email, 'Email address')
                    ->email('email', $email)
                    ->required('msg', $msg, 'Message');

                if (!$validator->passes()) {
                    $error = $validator->firstError();
                } else {
                    $inserted = $this->contactModel->create($fname, $lname, $email, $msg);

                    if ($inserted) {
                        $success = 'Your message has been sent successfully.';
                        $_POST = [];
                    } else {
                        $error = 'Failed to send your message. Please try again.';
                    }
                }
            }
        }

        $this->render('frontend/contact_us.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'post_data' => $_POST
        ]);
    }
}
