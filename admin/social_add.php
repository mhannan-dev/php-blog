<?php
require_once __DIR__ . '/../app/bootstrap.php';

(new \App\Controllers\Admin\SocialController($twig, $siteModel))->create();
