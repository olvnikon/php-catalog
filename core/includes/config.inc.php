<?php

require_once ROOT_PATH . 'core/includes/config.path.inc.php';
if (stream_resolve_include_path('config.local.inc.php')) {
    require_once ROOT_PATH . 'core/includes/config.local.inc.php';
}

@define('_DB_addr', 'localhost');
@define('_DB_name', '');
@define('_DB_login', '');
@define('_DB_pass', '');
@define('CFG_SITE_DOMAIN', '');

//Система оплаты
define('ROBOKASSA_LOGIN', '');
define('ROBOKASSA_PASS1', '');
define('ROBOKASSA_PASS2', '');
//Были в тестовом режиме
define('ROBOKASSA_PASS1_TEST', '');
define('ROBOKASSA_PASS2_TEST', '');

//ToDo: убить эти константы
define('UTYPE_ADMIN', 1);
define('UTYPE_MODERATOR', 2);
define('UTYPE_CLIENT', 3);
//ToDo: это вымрет
define('MENU_PLACE_INFO', 1);
define('MENU_PLACE_WHY', 2);
define('MENU_PLACE_ACCOUNT', 3);

define('PAGE_TYPE_WHY', 1);
define('PAGE_TYPE_DELIVERY', 2);
define('PAGE_TYPE_SHOPS', 4);
define('PAGE_TYPE_DISCOUNT', 5);
define('PAGE_TYPE_BIGBUY', 6);
define('PAGE_TYPE_COLLECTIVE', 7);
define('PAGE_TYPE_ACCOUNT_GREETING', 8);
define('PAGE_TYPE_AUTH_HEADER', 9);
define('PAGE_TYPE_LOGIN_NEW', 10);
define('PAGE_TYPE_LOGIN_OLD', 11);
define('PAGE_TYPE_REGISTRATION_HEADER', 12);
define('PAGE_TYPE_REGISTRATION_PARAGRAPH', 13);
define('PAGE_TYPE_FORGOT_HEADER', 14);
define('PAGE_TYPE_FORGOT_PARAGRAPH', 15);
define('PAGE_TYPE_CART_BILL', 16);
define('PAGE_TYPE_CONTACTS', 17);
define('PAGE_TYPE_PAYMENT_SUCCESS', 18);
define('PAGE_TYPE_PAYMENT_ERROR', 19);
define('PAGE_TYPE_PAYMENT_PROCESS', 20);
//?????
define('PAGE_TYPE_INDEX', 1);
//ToDo: это должна быть настройка
define('IMAGE_MAX_WIDTH', 1024);
define('IMAGE_MAX_HEIGHT', 1024);

require_once ROOT_PATH . 'bootstrap.php';
