<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class BannerEditor extends AbstractEditor
{

    protected $_requiredFields = array('name', 'state');
    protected $_entityName = 'Banner';

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Имя'),
            'content' => array(Field::TEXTEDITOR, 'Код'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            )
        );
    }

}
