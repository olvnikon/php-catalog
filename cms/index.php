<?php

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'core/includes/config.inc.php';
include_once CFG_PATH_CMS_INCLUDES . 'menu.php';

$config = include ROOT_PATH . 'core/includes/app.config.php';
$app = new Application($config);
$app->runCms();
