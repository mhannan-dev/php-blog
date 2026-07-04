<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use Site;
use Session;
use mysqli_result;

class SocialController extends BaseController
{
    private Site $siteModel;

    public function __construct(Environment $twig, Site $siteModel)
    {
        parent::__construct($twig);
        $this->siteModel = $siteModel;
    }

    public function list(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if (isset($_GET['del_social'])) {
            $delId = (int) $_GET['del_social'];
            if ($delId > 0) {
                $deleted = $this->siteModel->deleteSocial($delId);
                if ($deleted) {
                    $success = 'Social entry deleted successfully.';
                } else {
                    $error = 'Failed to delete social entry.';
                }
            }
        }

        $socialsResult = $this->siteModel->getAllSocial();
        $socials = [];
        if ($socialsResult && $socialsResult instanceof mysqli_result) {
            $socials = $socialsResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/social_list.twig', [
            'error'   => $error,
            'success' => $success,
            'socials' => $socials
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $platform = trim($_POST['platform'] ?? '');
                $icon     = trim($_POST['icon']     ?? '');
                $link     = trim($_POST['link']     ?? '');

                if ($platform === '' || $icon === '' || $link === '') {
                    $error = 'All fields are required.';
                } else {
                    $inserted = $this->siteModel->createSocial($platform, $icon, $link);

                    if ($inserted) {
                        header('Location: social_list.php');
                        exit();
                    } else {
                        $error = 'Failed to add social link. Please try again.';
                    }
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

        if (!isset($_GET['social_id']) || (int) $_GET['social_id'] <= 0) {
            header('Location: social_list.php');
            exit();
        }

        $socialId = (int) $_GET['social_id'];
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $platform = trim($_POST['platform'] ?? '');
                $icon     = trim($_POST['icon']     ?? '');
                $link     = trim($_POST['link']     ?? '');

                if ($platform === '' || $icon === '' || $link === '') {
                    $error = 'All fields are required.';
                } else {
                    $updated = $this->siteModel->updateSocial($socialId, $platform, $icon, $link);
                    if ($updated) {
                        header('Location: social_list.php');
                        exit();
                    } else {
                        $error = 'Failed to update social entry. Please try again.';
                    }
                }
            }
        }

        $socialData = $this->siteModel->getSocialById($socialId);
        if (!$socialData) {
            header('Location: social_list.php');
            exit();
        }

        $this->render('dashboard/social_edit.twig', [
            'error'      => $error,
            'success'    => $success,
            'csrfToken'  => Session::getCsrfToken(),
            'socialData' => $socialData
        ]);
    }
}
