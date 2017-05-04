<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ContentEditorCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Статичные страницы';

    /**
     *
     * @return \ContentEditorCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new StaticPageManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \ContentEditorCmsPage
     */
    protected function _initList()
    {
        $this->_list = new StaticPagesList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return StaticPageEditor
     */
    protected function _getEditor($item)
    {
        return new StaticPageEditor($item);
    }

}
