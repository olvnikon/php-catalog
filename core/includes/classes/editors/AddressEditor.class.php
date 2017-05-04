<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class AddressEditor extends AbstractAccountEditor
{

    protected $_requiredFields = array(
        'name', 'phone', 'address', 'town', 'postcode'
    );

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Имя'),
            'lastName' => array(Field::TEXT, 'Фамилия'),
            'company' => array(Field::TEXT, 'Компания'),
            'companyPhone' => array(Field::TEXT, 'Ваш телефон'),
            'fax' => array(Field::TEXT, 'Факс'),
            'address' => array(Field::TEXT, 'Улица, дом, квартира, корпус'),
            'town' => array(Field::TEXT, 'Город'),
            'postcode' => array(Field::TEXT, 'Почтовый индекс')
        );
    }

}
