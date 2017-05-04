<?php

/**
 * @author Никонов Владимир Андреевич
 */
class EmailsSubscriptionCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Подписанные Email';

    /**
     *
     * @return \SubscriptionEmailManager
     */
    protected function _initManager()
    {
        $this->_manager = new SubscriptionEmailManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \SubscriptionEmailsList
     */
    protected function _initList()
    {
        $this->_list = new SubscriptionEmailsList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return SubscriptionEmailEditor
     */
    protected function _getEditor($item)
    {
        return new SubscriptionEmailEditor($item);
    }

}
