<?php
require_once __DIR__ . '/../app/bootstrap.php';

(new \App\Controllers\Admin\UserController($twig, $userModel))->view();