<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class ContactFeedbackEditor extends AbstractEditor
{

    protected $_template = 'feedback_editor.html';
    protected $_templateRoot = CFG_PATH_TPL;
    protected $_entityName = 'ContactFeedback';
    protected $_requiredFields = array('name', 'email', 'content');

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Ваше имя'),
            'email' => array(Field::TEXT, 'Ваш Email'),
            'phone' => array(Field::TEXT, 'Ваш телефон'),
            'content' => array(Field::TEXTAREA, 'Описание')
        );
    }

    protected function _processSpecialFields()
    {
        $this->_item->readState = 0;
    }

    protected function _setFieldValue($field, $value)
    {
        parent::_setFieldValue($field, strip_tags($value));
    }

}
