<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class AccountEditor extends AbstractAccountEditor
{

    protected $_requiredFields = array('name', 'email', 'phone');

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Имя'),
            'lastName' => array(Field::TEXT, 'Фамилия'),
            'email' => array(Field::TEXT, 'Ваш Email'),
            'phone' => array(Field::TEXT, 'Ваш телефон')
        );
    }

}
