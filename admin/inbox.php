<?php
require_once __DIR__ . '/../app/bootstrap.php';

(new \App\Controllers\Admin\InboxController($twig, $contactModel))->index();
