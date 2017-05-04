<?php

/**
 * @author Никонов Владимир Андреевич
 */
class FeedbackCmsPage extends AbstractFeedbackCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Отзывы';

    /**
     *
     * @return \FeedbackCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new FeedbackManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \FeedbackCmsPage
     */
    protected function _initList()
    {
        $this->_list = new FeedbacksList();
        return parent::_initList();
    }

    /**
     * Инициализация редактора
     *
     * @return void
     */
    protected function _getEditor($item)
    {
        return new FeedbackEditor($item);
    }

}
