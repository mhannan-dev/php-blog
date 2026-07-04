<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Twig\Environment;
use Site;
use Session;
use mysqli_result;

class SliderController extends BaseController
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

        if (isset($_GET['delete_slider'])) {
            $delId = (int) $_GET['delete_slider'];
            if ($delId > 0) {
                $deleted = $this->siteModel->deleteSlider($delId);
                if ($deleted) {
                    $success = 'Slider deleted successfully.';
                } else {
                    $error = 'Failed to delete slider.';
                }
            }
        }

        $slidersResult = $this->siteModel->getSliders(20);
        $sliders = [];
        if ($slidersResult && $slidersResult instanceof mysqli_result) {
            $sliders = $slidersResult->fetch_all(MYSQLI_ASSOC) ?: [];
        }

        $this->render('dashboard/slider_list.twig', [
            'error'   => $error,
            'success' => $success,
            'sliders' => $sliders
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
                $title       = trim($_POST['title']       ?? '');
                $description = trim($_POST['description'] ?? '');

                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                $file        = $_FILES['image'] ?? null;
                $fileName    = $file['name']     ?? '';
                $fileSize    = $file['size']     ?? 0;
                $fileTmp     = $file['tmp_name'] ?? '';
                $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if ($title === '' || $fileName === '') {
                    $error = 'Title and Image are required.';
                } elseif ($fileSize > 1_048_576) {
                    $error = 'Image size must be less than 1 MB.';
                } elseif (!in_array($fileExt, $allowedExts, true)) {
                    $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
                } else {
                    $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
                    $uploadedPath = 'upload/slider/' . $uniqueName;

                    if (!is_dir(__DIR__ . '/../../../admin/upload/slider')) {
                        mkdir(__DIR__ . '/../../../admin/upload/slider', 0777, true);
                    }

                    if (!move_uploaded_file($fileTmp, __DIR__ . '/../../../admin/' . $uploadedPath)) {
                        $error = 'Failed to upload image. Check folder permissions.';
                    } else {
                        $inserted = $this->siteModel->createSlider($title, $description, $uploadedPath);

                        if ($inserted) {
                            header('Location: slider_list.php');
                            exit();
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

        if (!isset($_GET['slider_id']) || (int) $_GET['slider_id'] <= 0) {
            header('Location: slider_list.php');
            exit();
        }

        $sliderId = (int) $_GET['slider_id'];
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::checkCsrfToken($csrfToken)) {
                $error = 'Security check failed. Please refresh the page.';
            } else {
                $title       = trim($_POST['title']       ?? '');
                $description = trim($_POST['description'] ?? '');

                $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                $file        = $_FILES['image'] ?? null;
                $fileName    = $file['name']     ?? '';
                $fileSize    = $file['size']     ?? 0;
                $fileTmp     = $file['tmp_name'] ?? '';
                $fileExt     = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if ($title === '') {
                    $error = 'Title is required.';
                } else {
                    if (!empty($fileName)) {
                        if ($fileSize > 1_048_576) {
                            $error = 'Image size must be less than 1 MB.';
                        } elseif (!in_array($fileExt, $allowedExts, true)) {
                            $error = 'Allowed image types: ' . implode(', ', $allowedExts) . '.';
                        } else {
                            $uniqueName   = bin2hex(random_bytes(8)) . '.' . $fileExt;
                            $uploadedPath = 'upload/slider/' . $uniqueName;

                            if (!is_dir(__DIR__ . '/../../../admin/upload/slider')) {
                                mkdir(__DIR__ . '/../../../admin/upload/slider', 0777, true);
                            }

                            if (!move_uploaded_file($fileTmp, __DIR__ . '/../../../admin/' . $uploadedPath)) {
                                $error = 'Failed to upload image.';
                            } else {
                                $updated = $this->siteModel->updateSlider($sliderId, $title, $description, $uploadedPath);
                                if ($updated) {
                                    header('Location: slider_list.php');
                                    exit();
                                } else {
                                    $error = 'Failed to update slider.';
                                }
                            }
                        }
                    } else {
                        $updated = $this->siteModel->updateSlider($sliderId, $title, $description, '');
                        if ($updated) {
                            header('Location: slider_list.php');
                            exit();
                        } else {
                            $error = 'Failed to update slider.';
                        }
                    }
                }
            }
        }

        $sliderData = $this->siteModel->getSliderById($sliderId);
        if (!$sliderData) {
            header('Location: slider_list.php');
            exit();
        }

        $this->render('dashboard/edit_slider.twig', [
            'error'      => $error,
            'success'    => $success,
            'csrfToken'  => Session::getCsrfToken(),
            'sliderData' => $sliderData
        ]);
    }
}
