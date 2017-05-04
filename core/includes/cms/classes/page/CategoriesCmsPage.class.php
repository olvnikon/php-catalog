<?php

/**
 * @author Никонов Владимир Андреевич
 */
class CategoriesCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Категории товаров';
    protected $_hasPaginator = FALSE;

    /**
     *
     * @return \CategoriesCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new CategoryManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \CategoriesCmsPage
     */
    protected function _initList()
    {
        $this->_list = new CategoriesList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return \CategoryEditor
     */
    protected function _getEditor($item)
    {
        return new CategoryEditor($item);
    }

}
