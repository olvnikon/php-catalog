<?php

/**
 * @author Никонов Владимир Андреевич
 */
class BlogCategoriesCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Категории блога';

    /**
     *
     * @return \BlogCategoriesCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new BlogCategoryManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \BlogCategoriesCmsPage
     */
    protected function _initList()
    {
        $this->_list = new BlogCategoriesList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return \BlogCategoryEditor
     */
    protected function _getEditor($item)
    {
        return new BlogCategoryEditor($item);
    }

}
