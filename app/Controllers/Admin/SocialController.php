<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\SiteRepositoryInterface;
use App\Security\InputValidator;
use Session;
use Twig\Environment;

class SocialController extends BaseController
{
    private SiteRepositoryInterface $siteModel;

    public function __construct(Environment $twig, SiteRepositoryInterface $siteModel)
    {
        parent::__construct($twig);
        $this->siteModel = $siteModel;
    }

    public function list(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        $delId = $this->getIntParam('del_social');
        if ($delId > 0) {
            $deleted = $this->siteModel->deleteSocial($delId);
            if ($deleted) {
                $success = 'Social entry deleted successfully.';
            } else {
                $error = 'Failed to delete social entry.';
            }
        }

        $this->render('dashboard/social_list.twig', [
            'error'   => $error,
            'success' => $success,
            'socials' => $this->siteModel->getAllSocial()
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $this->render('dashboard/social_add.twig', [
                    'error'       => $error,
                    'success'     => $success,
                    'csrfToken'   => Session::getCsrfToken(),
                    'social_data' => $_POST
                ]);
                return;
            }

            $platform = $this->getRequestBody('platform');
            $icon     = $this->getRequestBody('icon');
            $link     = $this->getRequestBody('link');

            $validator = new InputValidator();
            $validator
                ->required('platform', $platform, 'Platform')
                ->required('icon', $icon, 'Icon')
                ->required('link', $link, 'Link')
                ->url('link', $link);

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $inserted = $this->siteModel->createSocial($platform, $icon, $link);
                if ($inserted) {
                    $this->redirect('social_list.php');
                } else {
                    $error = 'Failed to add social link. Please try again.';
                }
            }
        }

        $this->render('dashboard/social_add.twig', [
            'error'       => $error,
            'success'     => $success,
            'csrfToken'   => Session::getCsrfToken(),
            'social_data' => $_POST
        ]);
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $socialId = $this->getIntParam('social_id');
        if ($socialId <= 0) {
            $this->redirect('social_list.php');
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $socialData = $this->siteModel->getSocialById($socialId);
                $this->render('dashboard/social_edit.twig', [
                    'error'      => $error,
                    'success'    => $success,
                    'csrfToken'  => Session::getCsrfToken(),
                    'socialData' => $socialData
                ]);
                return;
            }

            $platform = $this->getRequestBody('platform');
            $icon     = $this->getRequestBody('icon');
            $link     = $this->getRequestBody('link');

            $validator = new InputValidator();
            $validator
                ->required('platform', $platform, 'Platform')
                ->required('icon', $icon, 'Icon')
                ->required('link', $link, 'Link')
                ->url('link', $link);

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $updated = $this->siteModel->updateSocial($socialId, $platform, $icon, $link);
                if ($updated) {
                    $this->redirect('social_list.php');
                } else {
                    $error = 'Failed to update social entry. Please try again.';
                }
            }
        }

        $socialData = $this->siteModel->getSocialById($socialId);
        if (!$socialData) {
            $this->redirect('social_list.php');
        }

        $this->render('dashboard/social_edit.twig', [
            'error'      => $error,
            'success'    => $success,
            'csrfToken'  => Session::getCsrfToken(),
            'socialData' => $socialData
        ]);
    }
}
