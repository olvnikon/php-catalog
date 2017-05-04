<?php

/**
 * @author Никонов Владимир Андреевич
 */
class PurchasesCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Покупки';

    /**
     *
     * @return \PurchasesCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new PurchaseManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \PurchasesCmsPage
     */
    protected function _initList()
    {
        $this->_list = new PurchasesList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return \PurchaseEditor
     */
    protected function _getEditor($item)
    {
        return new PurchaseEditor($item);
    }

}
