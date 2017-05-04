<?php

/**
 * @author Никонов Владимир Андреевич
 */
class SendEmailPage extends AbstractSitePage
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
        if (!self::_isEmptyMandatoryFields()) {
            self::_sendEmail();
        }

        if (empty($_SERVER['HTTP_REFERER'])) {
            Request::goToLocalPage('contacts');
        } else {
            Request::goLocation($_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * Проверка обязательных полей
     *
     * @return boolean
     */
    private static function _isEmptyMandatoryFields()
    {
        return Request::isEmpty('content')
            || Request::isEmpty('fio')
            || Request::isEmpty('email');
    }

    /**
     * Отправить Email
     *
     * @return void
     */
    private static function _sendEmail()
    {
        mb_internal_encoding("UTF-8");
        mb_send_mail(
            $GLOBALS['SettingManager']->getParamValue('Email для получения писем с сайта'),
            self::_getSubject(), self::_getMessage(), self::_getHeaders()
        );
    }

    /**
     * Получить тему сообщения
     *
     * @return string
     */
    private static function _getSubject()
    {
        return 'Новое сообщение от ' . Request::get('fio') . ' ' . date("Y-m-d H:m:s");
    }

    /**
     * Получить текст письма
     *
     * @return string
     */
    private static function _getMessage()
    {
        $message = "Текст: " . Request::get('content') . "\n";
        $message .= "От: " . Request::get('fio') . "\n";
        $message .= "Email: " . Request::get('email') . "\n";
        return $message;
    }

    /**
     * Получить заголовки письма
     *
     * @return string
     */
    private static function _getHeaders()
    {
        require_once CFG_PATH_DB_CLASS . 'Setting.class.php';
        $title = $GLOBALS['SettingManager']->getParamValue('Название сайта (title)');
        $headers = "Content-type: text/html; charset=UTF-8 \r\n";
        $headers .= "From: \"" . $title . "\"\r\n";
        return $headers;
    }

}
