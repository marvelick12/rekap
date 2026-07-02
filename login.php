<?php
// login.php entry point

require_once __DIR__ . '/controllers/AuthController.php';

$auth = new AuthController();
$auth->login();
