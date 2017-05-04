<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ArticleCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Создаваемые страницы';

    /**
     *
     * @var boolean
     */
    protected $_hasPaginator = FALSE;

    /**
     *
     * @return \ArticleCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new ArticleManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \ArticleCmsPage
     */
    protected function _initList()
    {
        $this->_list = new ArticlesList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return void
     */
    protected function _getEditor($item)
    {
        return new ArticleEditor($item);
    }

}
