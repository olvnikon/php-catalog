<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ContactFeedbackCmsPage extends AbstractFeedbackCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Сообщения обратной связи';

    /**
     *
     * @return \FeedbackCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new ContactFeedbackManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \FeedbackCmsPage
     */
    protected function _initList()
    {
        $this->_list = new ContactFeedbacksList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return ContactFeedbackCmsEditor
     */
    protected function _getEditor($item)
    {
        return new ContactFeedbackCmsEditor($item);
    }

}
