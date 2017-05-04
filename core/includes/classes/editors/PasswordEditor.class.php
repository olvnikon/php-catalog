<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class PasswordEditor extends AbstractAccountEditor
{

    protected $_requiredFields = array(
        'oldPassword', 'nePasswd', 'nePasswdRepeat'
    );

    protected function _getControls()
    {
        return array(
            'oldPassword' => array(Field::PASSWORD, 'Старый пароль'),
            'nePasswd' => array(Field::PASSWORD, 'Пароль'),
            'nePasswdRepeat' => array(Field::PASSWORD, 'Повторите пароль'),
        );
    }

    /**
     *
     * @return boolean
     */
    protected function _checkAfterProcess()
    {
        return $this->_item->nePasswd == $this->_item->nePasswdRepeat
            && Application::getLoggedUser()->nePasswd == $this->_item->oldPassword;
    }

    protected function _processSpecialFields()
    {
        $this->_item->password = md5($this->_item->nePasswd);
    }

    /**
     * @return void
     */
    protected function _saveToDB()
    {
        parent::_saveToDB();
        if (!Cookie::isEmpty('login')) {
            Cookie::set('login', $this->_item->email);
            Cookie::set('password', $this->_item->nePasswd);
        }
    }

}
