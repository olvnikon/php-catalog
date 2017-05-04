<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class SettingsList extends AbstractList
{

    protected $_entityName = 'Setting';
    protected $_sortable = TRUE;

    protected function _getFields()
    {
        return array(
            'paramName' => array(Field::CAPTION, 'Описание'),
            'paramValue' => array(Field::CAPTION, 'Данные')
        );
    }

}
