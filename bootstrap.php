<?php

/**
 *
 * @param string $class
 * @return void
 * @throws Exception
 */
function __autoloadPages($class)
{
    $filename = $class . '.class.php';
    if (_tryAutoloadDBClass($class)
        || _tryFile(CFG_PATH_CLASS . $filename)
        || _tryFile(CFG_PATH_BUILDER . $filename)
        || _tryFile(CFG_PATH_VIEW . $filename)
        || _tryFile(CFG_PATH_PAGE . $filename)
        || _tryFile(CFG_PATH_CMS_PAGE . $filename)
        || _tryFile(CFG_PATH_CMS_LIST . $filename)
        || _tryFile(CFG_PATH_CMS_EDITOR . $filename)
        || _tryFile(CFG_PATH_EDITOR . $filename)) {
        return TRUE;
    }

    throw new Exception("Class $class does not exists!");
}

/**
 * CFG_PATH_DB_CLASS
 * @param string $class
 * @return boolean
 */
function _tryAutoloadDBClass($class)
{
    if (preg_match('/\w+Manager$/', $class)) {
        $class = str_replace('Manager', '', $class);
    }

    return _tryFile(CFG_PATH_DB_CLASS . $class . '.class.php');
}

/**
 *
 * @param string $fileName
 * @return boolean
 */
function _tryFile($fileName)
{
    if (file_exists($fileName)) {
        require_once $fileName;
        return TRUE;
    }

    return FALSE;
}

spl_autoload_register('__autoloadPages');
