<?php

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'core/includes/config.inc.php';

require_once 'captcha/API.php';
Captcha\API::showCaptcha();
