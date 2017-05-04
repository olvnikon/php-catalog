<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ReviewsPage extends AbstractSitePage
{

    /**
     *
     * @var Template
     */
    private $_reviewsTpl;

    protected function _initTpl()
    {
        parent::_initTpl();
        $this->_reviewsTpl = new Template(CFG_PATH_TEMPLATE . 'reviews.html');
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $this->_tpl->setVar('Page-Keywords', ', отзывы');
        $this->_tpl->setVar('Page-Title', 'Отзывы');

        $this->_parseFeedbacks();
        $this->_tpl->setVar(
            'Page-Content', $this->_reviewsTpl->fillTemplate(), TRUE
        );
    }

    /**
     * Распарсить отзывы
     *
     * @return void
     */
    private function _parseFeedbacks()
    {
        $this->_reviewsTpl->setVar('Feedbacks', '');
        $fm = new FeedbackManager();
        $feedbacks = $fm->getAll('state = 1', array(), 0, 100);
        foreach ($feedbacks AS $feedback) {
            $this->_reviewsTpl->setVar('Feedback-CreateDate',
                $feedback->createDate);
            $this->_reviewsTpl->setVar('Feedback-Fio', $feedback->fio);
            $this->_reviewsTpl->setVar('Feedback-Content', $feedback->content);
            $this->_reviewsTpl->parseB2V('Feedbacks', 'FEEDBACK', TRUE);
        }
    }

    /**
     * Обработка запросов
     *
     * @return void
     */
    public function process()
    {
        $this->_reviewsTpl->setVar('Form-Name', Request::get('fio'));
        $this->_reviewsTpl->setVar('Form-Content', Request::get('content'));
        if (!Request::isEmpty('error')) {
            $this->_reviewsTpl->parseB2V('PostState', 'CAPTCHA-ERROR');
        }
        if (!Request::isEmpty('success')) {
            $this->_reviewsTpl->parseB2V('PostState', 'CAPTCHA-SUCCESS');
        }
        if (!Request::isEmpty('empty')) {
            $this->_reviewsTpl->parseB2V('PostState', 'FORM-EMPTY');
        }
    }

}
