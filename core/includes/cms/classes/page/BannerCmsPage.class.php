<?php

/**
 * @author Никонов Владимир Андреевич
 */
class BannerCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Баннеры каталога';

    /**
     *
     * @return \BannerCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new BannerManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \BannerCmsPage
     */
    protected function _initList()
    {
        $this->_list = new BannersList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return \BannerEditor
     */
    protected function _getEditor($item)
    {
        return new BannerEditor($item);
    }

}
