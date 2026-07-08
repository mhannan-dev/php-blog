<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\SiteRepositoryInterface;
use App\Security\InputValidator;
use Session;
use Twig\Environment;

class SiteSettingsController extends BaseController
{
    private SiteRepositoryInterface $siteModel;

    public function __construct(Environment $twig, SiteRepositoryInterface $siteModel)
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
            if (!$this->validateCsrf($_POST, $error)) {
                $sloganData = $this->siteModel->getInfo();
                $this->render('dashboard/titleslogan.twig', [
                    'error'      => $error,
                    'success'    => $success,
                    'csrfToken'  => Session::getCsrfToken(),
                    'sloganData' => $sloganData
                ]);
                return;
            }

            $title  = $this->getRequestBody('title');
            $slogan = $this->getRequestBody('slogan');

            $validator = new InputValidator();
            $validator
                ->required('title', $title, 'Title')
                ->required('slogan', $slogan, 'Slogan');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $allowedExts = ['png'];
                $file        = $_FILES['logo'] ?? null;
                $fileName    = $file['name'] ?? '';

                if (!empty($fileName)) {
                    $fileSize = $file['size'] ?? 0;
                    $fileTmp  = $file['tmp_name'] ?? '';
                    $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if ($fileSize > 1_048_576) {
                        $error = 'Image size must be less than 1 MB.';
                    } elseif (!in_array($fileExt, $allowedExts, true)) {
                        $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
                    } else {
                        $uploadedPath = 'upload/logo.png';
                        if (!move_uploaded_file($fileTmp, __DIR__ . '/../../../admin/' . $uploadedPath)) {
                            $error = 'Failed to upload logo.';
                        } else {
                            $updated = $this->siteModel->updateSiteInfo(1, $title, $slogan, $uploadedPath);
                            if ($updated) {
                                $success = 'Data updated successfully.';
                            } else {
                                $error = 'Failed to update data.';
                            }
                        }
                    }
                } else {
                    $updated = $this->siteModel->updateSiteInfo(1, $title, $slogan, null);
                    if ($updated) {
                        $success = 'Data updated successfully.';
                    } else {
                        $error = 'Failed to update data.';
                    }
                }
            }
        }

        $sloganData = $this->siteModel->getInfo() ?: false;

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
            if (!$this->validateCsrf($_POST, $error)) {
                $copyrightData = $this->siteModel->getFooterNote();
                $this->render('dashboard/copyright.twig', [
                    'error'         => $error,
                    'success'       => $success,
                    'csrfToken'     => Session::getCsrfToken(),
                    'copyrightData' => $copyrightData
                ]);
                return;
            }

            $note = $this->getRequestBody('copyright');

            $validator = new InputValidator();
            $validator->required('copyright', $note, 'Copyright note');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $updated = $this->siteModel->updateFooter($note);
                if ($updated) {
                    $success = 'Data updated successfully.';
                } else {
                    $error = 'Failed to update data.';
                }
            }
        }

        $copyrightData = $this->siteModel->getFooterNote() ?: false;

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

        $delId = $this->getIntParam('site_info_id');
        if ($delId > 0) {
            $deleted = $this->siteModel->deleteSiteInfo($delId);
            if ($deleted) {
                $success = 'Information deleted successfully.';
            } else {
                $error = 'Failed to delete information.';
            }
        }

        $this->render('dashboard/site_info_list.twig', [
            'error'   => $error,
            'success' => $success,
            'infos'   => $this->siteModel->getAllSiteInfo()
        ]);
    }

    public function editInfo(): void
    {
        $this->requireAdmin();

        $infoId = $this->getIntParam('site_info_id');
        if ($infoId <= 0) {
            $this->redirect('site_info_list.php');
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $infoData = $this->siteModel->getSiteInfoById($infoId);
                $this->render('dashboard/edit_site_info.twig', [
                    'error'     => $error,
                    'success'   => $success,
                    'csrfToken' => Session::getCsrfToken(),
                    'infoData'  => $infoData
                ]);
                return;
            }

            $contactEmail = $this->getRequestBody('contact_email');
            $contactPhone = $this->getRequestBody('contact_phone');
            $contactAdd   = $this->getRequestBody('contact_add');
            $aboutUs      = $this->getRequestBody('about_us');

            $validator = new InputValidator();
            $validator
                ->required('contact_email', $contactEmail, 'Contact email')
                ->email('contact_email', $contactEmail)
                ->required('contact_phone', $contactPhone, 'Contact phone')
                ->required('contact_add', $contactAdd, 'Contact address')
                ->required('about_us', $aboutUs, 'About us');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $updated = $this->siteModel->updateSiteInfo($infoId, $contactEmail, $contactPhone);
                if ($updated) {
                    $success = 'Information updated successfully.';
                } else {
                    $error = 'Failed to update information.';
                }
            }
        }

        $infoData = $this->siteModel->getSiteInfoById($infoId);
        if (!$infoData) {
            $this->redirect('site_info_list.php');
        }

        $this->render('dashboard/edit_site_info.twig', [
            'error'     => $error,
            'success'   => $success,
            'csrfToken' => Session::getCsrfToken(),
            'infoData'  => $infoData
        ]);
    }
}
