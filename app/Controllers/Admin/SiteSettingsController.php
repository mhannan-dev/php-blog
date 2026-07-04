<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use Site;
use Session;
use mysqli_result;

class SiteSettingsController extends BaseController
{
    private Site $siteModel;

    public function __construct(Environment $twig, Site $siteModel)
    {
        parent::__construct($twig);
        $this->siteModel = $siteModel;
    }

    public function titleSlogan(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $title  = trim($_POST['title']  ?? '');
                $slogan = trim($_POST['slogan'] ?? '');

                $allowedExts = ['png'];
                $file        = $_FILES['logo'] ?? null;
                $fileName    = $file['name']     ?? '';
                $fileSize    = $file['size']     ?? 0;
                $fileTmp     = $file['tmp_name'] ?? '';
                $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if ($title === '' || $slogan === '') {
                    $error = 'Title and Slogan must not be empty.';
                } else {
                    if (!empty($fileName)) {
                        if ($fileSize > 1_048_576) {
                            $error = 'Image size must be less than 1 MB.';
                        } elseif (!in_array($fileExt, $allowedExts, true)) {
                            $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
                        } else {
                            $uploadedPath = 'upload/logo.png';
                            if (!move_uploaded_file($fileTmp, __DIR__ . '/../../../admin/' . $uploadedPath)) {
                                $error = 'Failed to upload logo.';
                            } else {
                                $updated = $this->siteModel->updateTitleSlogan(1, $title, $slogan, $uploadedPath);
                                if ($updated) {
                                    $success = 'Data updated successfully.';
                                } else {
                                    $error = 'Failed to update data.';
                                }
                            }
                        }
                    } else {
                        $updated = $this->siteModel->updateTitleSlogan(1, $title, $slogan, '');
                        if ($updated) {
                            $success = 'Data updated successfully.';
                        } else {
                            $error = 'Failed to update data.';
                        }
                    }
                }
            }
        }

        $sloganData = current($this->siteModel->getTitleSlogan() ?? []) ?: false;

        $this->render('dashboard/titleslogan.twig', [
            'error'      => $error,
            'success'    => $success,
            'csrfToken'  => Session::getCsrfToken(),
            'sloganData' => $sloganData
        ]);
    }

    public function copyright(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $note = trim($_POST['copyright'] ?? '');

                if ($note === '') {
                    $error = 'Field must not be empty.';
                } else {
                    $updated = $this->siteModel->updateFooter(1, $note);
                    if ($updated) {
                        $success = 'Data updated successfully.';
                    } else {
                        $error = 'Failed to update data.';
                    }
                }
            }
        }

        $copyrightData = current($this->siteModel->getFooter() ?? []) ?: false;

        $this->render('dashboard/copyright.twig', [
            'error'         => $error,
            'success'       => $success,
            'csrfToken'     => Session::getCsrfToken(),
            'copyrightData' => $copyrightData
        ]);
    }

    public function infoList(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if (isset($_GET['site_info_id'])) {
            $delId = (int) $_GET['site_info_id'];
            if ($delId > 0) {
                $deleted = $this->siteModel->deleteSiteInfo($delId);
                if ($deleted) {
                    $success = 'Information deleted successfully.';
                } else {
                    $error = 'Failed to delete information.';
                }
            }
        }

        $infosResult = $this->siteModel->getAllSiteInfo();
        $infos = [];
        if ($infosResult && $infosResult instanceof mysqli_result) {
            $infos = $infosResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/site_info_list.twig', [
            'error'   => $error,
            'success' => $success,
            'infos'   => $infos
        ]);
    }

    public function editInfo(): void
    {
        $this->requireAdmin();

        if (!isset($_GET['site_info_id']) || (int) $_GET['site_info_id'] <= 0) {
            header('Location: site_info_list.php');
            exit();
        }

        $infoId  = (int) $_GET['site_info_id'];
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $contactEmail = trim($_POST['contact_email'] ?? '');
                $contactPhone = trim($_POST['contact_phone'] ?? '');
                $contactAdd   = trim($_POST['contact_add']   ?? '');
                $aboutUs      = trim($_POST['about_us']      ?? '');

                if ($contactEmail === '' || $contactPhone === '' || $contactAdd === '' || $aboutUs === '') {
                    $error = 'Fields must not be empty.';
                } else {
                    $updated = $this->siteModel->updateSiteInfo($infoId, $contactEmail, $contactPhone, $contactAdd, $aboutUs);
                    if ($updated) {
                        $success = 'Information updated successfully.';
                    } else {
                        $error = 'Failed to update information.';
                    }
                }
            }
        }

        $infoData = $this->siteModel->getSiteInfoById($infoId);
        if (!$infoData) {
            header('Location: site_info_list.php');
            exit();
        }

        $this->render('dashboard/edit_site_info.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'infoData'  => $infoData
        ]);
    }
}
