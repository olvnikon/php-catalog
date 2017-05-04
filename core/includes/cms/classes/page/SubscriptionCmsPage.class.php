<?php

/**
 * @author Никонов Владимир Андреевич
 */
class SubscriptionCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Новости';

    /**
     *
     * @return \SubscriptionArticleManager
     */
    protected function _initManager()
    {
        $this->_manager = new SubscriptionArticleManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \SubscriptionArticlesList
     */
    protected function _initList()
    {
        $this->_list = new SubscriptionArticlesList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return \SubscriptionArticleEditor
     */
    protected function _getEditor($item)
    {
        return new SubscriptionArticleEditor($item);
    }

}
