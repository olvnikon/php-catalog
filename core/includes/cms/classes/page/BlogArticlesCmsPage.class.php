<?php

/**
 * @author Никонов Владимир Андреевич
 */
class BlogArticlesCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Статья блога';

    /**
     *
     * @return \BlogArticlesCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new BlogArticleManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \BlogArticlesCmsPage
     */
    protected function _initList()
    {
        $this->_list = new BlogArticlesList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return \BlogArticleEditor
     */
    protected function _getEditor($item)
    {
        return new BlogArticleEditor($item);
    }

}
