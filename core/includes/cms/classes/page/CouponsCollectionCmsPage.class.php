<?php

/**
 * @author Никонов Владимир Андреевич
 */
class CouponsCollectionCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Коллекции купонов';

    /**
     *
     * @return \CouponsCollectionCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new CouponCollectionManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \CouponsCollectionCmsPage
     */
    protected function _initList()
    {
        $this->_list = new CouponsCollectionsList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return \CouponCollectionEditor
     */
    protected function _getEditor($item)
    {
        return new CouponCollectionEditor($item);
    }

}
