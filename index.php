<?php

define('ROOT_PATH', '');
require_once ROOT_PATH . 'core/includes/config.inc.php';

$config = include ROOT_PATH . 'core/includes/app.config.php';
$app = new Application($config);
$app->run();
