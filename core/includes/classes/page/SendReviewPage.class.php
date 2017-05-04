<?php

/**
 * @author Никонов Владимир Андреевич
 */
class SendReviewPage extends AbstractSitePage
{

    /**
     * Отобразить содержимое страницы
     *
     * @return void
     */
    public function show()
    {

    }

    /**
     * Обработка запросов
     *
     * @return void
     */
    public function process()
    {
        Session::start();
        if (self::_isCorrectCaptcha()) {
            if (self::_isEmptyMandatoryFields()) {
                Request::goToLocalPage('reviews?empty=1');
            }

            self::_createFeedback();
            Request::goToLocalPage('reviews?success=1');
        } else {
            Request::goToLocalPage(
                'reviews?error=1&'
                . Request::refineQuery(array('page', 'captcha'))
            );
            echo 'Wrong';
        }
        unset($_SESSION['captcha']);
    }

    /**
     * Проверка обязательных полей
     *
     * @return boolean
     */
    private static function _isEmptyMandatoryFields()
    {
        return Request::isEmpty('content') || Request::isEmpty('fio');
    }

    /**
     * Создать отзыв
     *
     * @return void
     */
    private static function _createFeedback()
    {
        $feedback = new Feedback();
        $feedback->content = Request::get('content');
        $feedback->fio = Request::get('fio');
        $fm = new FeedbackManager();
        $fm->create($feedback);
    }

}
