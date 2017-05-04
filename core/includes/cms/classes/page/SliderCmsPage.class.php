<?php

/**
 * @author Никонов Владимир Андреевич
 */
class SliderCmsPage extends AbstractSimpleCmsPage
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
    protected $_breadTrails = 'Слайдер';

    /**
     *
     * @return \SettingsCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new SlideManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \SettingsCmsPage
     */
    protected function _initList()
    {
        $this->_list = new SlidesList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return void
     */
    protected function _getEditor($item)
    {
        return new SlideEditor($item);
    }

}
