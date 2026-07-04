<?php
require_once __DIR__ . '/app/bootstrap.php';

(new \App\Controllers\Frontend\ContactController($twig, $contactModel))->index();