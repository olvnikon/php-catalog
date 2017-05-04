<?php

define('CFG_PATH_INCLUDES', ROOT_PATH . 'core/includes/');
define('CFG_PATH_CLASS', CFG_PATH_INCLUDES . 'classes/');
define('CFG_PATH_EDITOR', CFG_PATH_CLASS . 'editors/');
define('CFG_PATH_LIB', CFG_PATH_INCLUDES . 'lib/');
define('CFG_PATH_DB_CLASS', CFG_PATH_LIB . 'db_classes/');
define('CFG_PATH_MAPPING', CFG_PATH_DB_CLASS . 'mapping/');
define('CFG_PATH_LIB_CLASS', CFG_PATH_LIB . 'classes/');
define('CFG_PATH_BUILDER', CFG_PATH_CLASS . 'builder/');
define('CFG_PATH_VIEW', CFG_PATH_CLASS . 'view/');
define('CFG_PATH_PAGE', CFG_PATH_CLASS . 'page/');
define('CFG_PATH_TPL', CFG_PATH_INCLUDES . 'tpl/');
define('CFG_PATH_CMS', CFG_PATH_INCLUDES . 'cms/');
define('CFG_PATH_TPL_CMS', CFG_PATH_CMS . 'tpl/');
define('CFG_PATH_CMS_CLASSES', CFG_PATH_CMS . 'classes/');
define('CFG_PATH_CMS_LIST', CFG_PATH_CMS_CLASSES . 'lists/');
define('CFG_PATH_CMS_EDITOR', CFG_PATH_CMS_CLASSES . 'editors/');
define('CFG_PATH_CMS_PAGE', CFG_PATH_CMS_CLASSES . 'page/');
define('CFG_PATH_TEMPLATE', ROOT_PATH . 'templates/');
define('CFG_PATH_TEMPLATE_CMS', ROOT_PATH . 'cms/templates/');
define('CFG_PATH_CMS_INCLUDES', ROOT_PATH . 'cms/includes/');
define('CFG_PATH_TMP', ROOT_PATH . 'tmp/');
define('CFG_IMAGES_FOLDER', 'images/');
define('CFG_PATH_TMP_IMAGES', CFG_PATH_TMP . CFG_IMAGES_FOLDER);
define('CFG_PATH_IMAGES', ROOT_PATH . CFG_IMAGES_FOLDER);

set_include_path(CFG_PATH_LIB_CLASS);
