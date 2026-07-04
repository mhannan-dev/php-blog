<?php
require_once __DIR__ . '/../app/bootstrap.php';

(new \App\Controllers\Admin\DashboardController($twig, $postModel, $categoryModel, $contactModel))->index();