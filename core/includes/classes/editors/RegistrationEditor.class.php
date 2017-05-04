<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class RegistrationEditor extends AbstractEditor
{

    protected $_template = 'registration_editor.html';
    protected $_templateRoot = CFG_PATH_TPL;
    protected $_entityName = 'User';
    protected $_requiredFields = array(
        'name', 'email', 'phone', 'nePasswd', 'nePasswdRepeat'
    );

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Имя'),
            'lastName' => array(Field::TEXT, 'Фамилия'),
            'email' => array(Field::TEXT, 'Ваш Email'),
            'phone' => array(Field::TEXT, 'Ваш телефон'),
            'nePasswd' => array(Field::PASSWORD, 'Ваш пароль'),
            'nePasswdRepeat' => array(Field::PASSWORD, 'Подтверждение пароля')
        );
    }

    public function getHTML()
    {
        if (!Request::isEmpty('success')) {
            $this->_tpl->parseB2V('Message', 'SUCCESS');
        }
        if (!Request::isEmpty('exists')) {
            $this->_tpl->parseB2V('Message', 'EXISTS');
        }
        return parent::getHTML();
    }

    /**
     *
     * @return boolean
     */
    protected function _checkAfterProcess()
    {
        if ($this->_item->nePasswd != $this->_item->nePasswdRepeat) {
            return FALSE;
        }

        if ($this->_manager->getCounts('email=:email',
                array('email' => $this->_item->email))) {
            Request::goToLocalPage('/registration?exists=1');
        }

        return TRUE;
    }

    protected function _processSpecialFields()
    {
        $this->_item->password = md5($this->_item->nePasswd);
        $this->_item->type = UTYPE_CLIENT;
        $this->_item->state = 0;
    }

    /**
     * @return void
     */
    protected function _goAfterProcess()
    {
        $this->_sendRegistrationMessage();
        Request::goToLocalPage('/registration?success=1');
    }

    /**
     * @return void
     */
    private function _sendRegistrationMessage()
    {
        require_once CFG_PATH_DB_CLASS . 'Setting.class.php';
        $title = $GLOBALS['SettingManager']->getParamValue('Название сайта (title)');

        mb_internal_encoding("UTF-8");
        mb_send_mail(
            $this->_item->email, 'Регистрация на сайте',
            $this->_getEmailContent(),
            "Content-type: text/html; charset=UTF-8 \r\n"
            . "From: \"" . $title . "\"\r\n"
        );
    }

    /**
     *
     * @return string
     */
    private function _getEmailContent()
    {
        $vcm = new VerificationCodeManager();
        $code = $vcm->createByUser($this->_item);

        $tpl = new Template(CFG_PATH_TPL . 'registration_email.html');
        $tpl->setVar('Registration-Link',
            CFG_SITE_DOMAIN . 'verification/' . $code->vCode);
        return $tpl->fillTemplate();
    }

}
