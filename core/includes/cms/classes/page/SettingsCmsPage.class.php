<?php

require_once CFG_PATH_DB_CLASS . 'Setting.class.php';

/**
 * @author Никонов Владимир Андреевич
 */
class SettingsCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var boolean
     */
    protected $_hasPaginator = FALSE;

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Дополнительно';

    /**
     *
     * @return \SettingsCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = $GLOBALS['SettingManager'];
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \SettingsCmsPage
     */
    protected function _initList()
    {
        $this->_list = new SettingsList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return void
     */
    protected function _getEditor($item)
    {
        return new SettingEditor($item);
    }

}
