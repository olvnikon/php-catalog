<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ProductCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Список продуктов';

    /**
     *
     * @return \ProductCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new ProductManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \ProductCmsPage
     */
    protected function _initList()
    {
        $this->_list = new ProductsList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return \ProductEditor
     */
    protected function _getEditor($item)
    {
        return new ProductEditor($item);
    }

}
