<?php

use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$app = AppFactory::create();

// add setting
// require_once __DIR__ . '/../src/setting.php';
// Add error middleware
require_once __DIR__ . '/../middleware.php';
// Add route
require_once __DIR__ . '/../route.php';

$app->run();
