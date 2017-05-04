<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ForgotPage extends AbstractSitePage
{

    public function process()
    {
        if (Request::isEmpty('email')) {
            return;
        }

        $um = new UserManager();
        $user = $um->getAll('email=:email AND state=1',
            array('email' => Request::get('email')));
        if (empty($user)) {
            Request::goToLocalPage('/forgot?not_existed=1');
        }

        $this->_resetPassword($user[0]);
        $um->update($user[0]);
        Request::goToLocalPage('/forgot?reset=1');
    }

    /**
     *
     * @param User $user
     * @return void
     */
    private function _resetPassword(User $user)
    {
        $user->nePasswd = uniqid($user->id);
        $user->password = md5($user->nePasswd);
        $this->_sendEmail($user);
    }

    /**
     *
     * @param User $user
     * @return void
     */
    private function _sendEmail(User $user)
    {
        require_once CFG_PATH_DB_CLASS . 'Setting.class.php';
        $title = $GLOBALS['SettingManager']->getParamValue('Название сайта (title)');

        mb_internal_encoding("UTF-8");
        mb_send_mail(
            $this->_item->email, 'Сброс пароля', $this->_getEmailContent($user),
            "Content-type: text/html; charset=UTF-8 \r\n"
            . "From: \"" . $title . "\"\r\n"
        );
    }

    /**
     *
     * @param User $user
     * @return string
     */
    private function _getEmailContent(User $user)
    {
        $tpl = new Template(CFG_PATH_TPL . 'forgot_email.html');
        $tpl->setVar('NewPassword', $user->nePasswd);
        return $tpl->fillTemplate();
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $tpl = new Template(CFG_PATH_TPL . 'forgot_password.html');
        $this->_parseDynamic($tpl);
        $this->_parseStaticContent($tpl);
        $this->_tpl->setVar('Page-Content', $tpl->fillTemplate());
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseDynamic(Template $tpl)
    {
        if (!Request::isEmpty('reset')) {
            $tpl->parseB2V('Message', 'SUCCESS');
        }

        if (!Request::isEmpty('not_existed')) {
            $tpl->parseB2V('Message', 'NO_USER');
        }
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseStaticContent(Template $tpl)
    {
        $spm = new StaticPageManager();
        $header = $spm->getById(PAGE_TYPE_FORGOT_HEADER);
        $tpl->setVar('Forgot-Header', $header->content);

        $paragraph = $spm->getById(PAGE_TYPE_FORGOT_PARAGRAPH);
        $tpl->setVar('Forgot-Paragraph', $paragraph->content);
    }

}
