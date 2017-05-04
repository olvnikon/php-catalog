<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ResourceCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Ресурсы';

    /**
     *
     * @return \ResourceCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new ResourceManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \ResourceCmsPage
     */
    protected function _initList()
    {
        $this->_list = new ResourcesList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return void
     */
    protected function _getEditor($item)
    {
        return new ResourceEditor($item);
    }

}
