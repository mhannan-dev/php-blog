<?php
require_once __DIR__ . '/app/bootstrap.php';

(new \App\Controllers\Frontend\HomeController($twig, $postModel, $siteModel))->index();