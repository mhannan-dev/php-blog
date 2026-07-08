<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Contracts\SiteRepositoryInterface;
use App\Security\InputValidator;
use App\Services\FileUploader;
use Session;
use Twig\Environment;

class SliderController extends BaseController
{
    private SiteRepositoryInterface $siteModel;
    private FileUploader $uploader;

    public function __construct(Environment $twig, SiteRepositoryInterface $siteModel)
    {
        parent::__construct($twig);
        $this->siteModel = $siteModel;
        $this->uploader  = new FileUploader(allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'], uploadDir: __DIR__ . '/../../../admin/');
    }

    public function list(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        $delId = $this->getIntParam('delete_slider');
        if ($delId > 0) {
            $deleted = $this->siteModel->deleteSlider($delId);
            if ($deleted) {
                $success = 'Slider deleted successfully.';
            } else {
                $error = 'Failed to delete slider.';
            }
        }

        $this->render('dashboard/slider_list.twig', [
            'error'   => $error,
            'success' => $success,
            'sliders' => $this->siteModel->getSliders(20)
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $this->render('dashboard/add_slider.twig', [
                    'error'       => $error,
                    'success'     => $success,
                    'csrfToken'   => Session::getCsrfToken(),
                    'slider_data' => $_POST
                ]);
                return;
            }

            $title       = $this->getRequestBody('title');
            $description = $this->getRequestBody('description');

            $validator = new InputValidator();
            $validator->required('title', $title, 'Title');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $fileError = $this->uploader->validate($_FILES['image'] ?? []);
                if ($fileError !== null) {
                    $error = $fileError;
                } else {
                    $result = $this->uploader->upload($_FILES['image'] ?? [], 'slider');
                    if (!$result['success']) {
                        $error = $result['error'];
                    } else {
                        $inserted = $this->siteModel->createSlider($title, $description, $result['path']);
                        if ($inserted) {
                            $this->redirect('slider_list.php');
                        } else {
                            $error = 'Failed to save the slider. Please try again.';
                        }
                    }
                }
            }
        }

        $this->render('dashboard/add_slider.twig', [
            'error'       => $error,
            'success'     => $success,
            'csrfToken'   => Session::getCsrfToken(),
            'slider_data' => $_POST
        ]);
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $sliderId = $this->getIntParam('slider_id');
        if ($sliderId <= 0) {
            $this->redirect('slider_list.php');
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validateCsrf($_POST, $error)) {
                $sliderData = $this->siteModel->getSliderById($sliderId);
                $this->render('dashboard/edit_slider.twig', [
                    'error'      => $error,
                    'success'    => $success,
                    'csrfToken'  => Session::getCsrfToken(),
                    'sliderData' => $sliderData
                ]);
                return;
            }

            $title       = $this->getRequestBody('title');
            $description = $this->getRequestBody('description');

            $validator = new InputValidator();
            $validator->required('title', $title, 'Title');

            if (!$validator->passes()) {
                $error = $validator->firstError();
            } else {
                $file = $_FILES['image'] ?? null;
                if ($file && !empty($file['name'])) {
                    $fileError = $this->uploader->validate($file);
                    if ($fileError !== null) {
                        $error = $fileError;
                    } else {
                        $result = $this->uploader->upload($file, 'slider');
                        if (!$result['success']) {
                            $error = $result['error'];
                        } else {
                            $updated = $this->siteModel->updateSlider($sliderId, $title, $result['path']);
                            if ($updated) {
                                $this->redirect('slider_list.php');
                            } else {
                                $error = 'Failed to update slider.';
                            }
                        }
                    }
                } else {
                    $updated = $this->siteModel->updateSlider($sliderId, $title, '');
                    if ($updated) {
                        $this->redirect('slider_list.php');
                    } else {
                        $error = 'Failed to update slider.';
                    }
                }
            }
        }

        $sliderData = $this->siteModel->getSliderById($sliderId);
        if (!$sliderData) {
            $this->redirect('slider_list.php');
        }

        $this->render('dashboard/edit_slider.twig', [
            'error'      => $error,
            'success'    => $success,
            'csrfToken'  => Session::getCsrfToken(),
            'sliderData' => $sliderData
        ]);
    }
}
