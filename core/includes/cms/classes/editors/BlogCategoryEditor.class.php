<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class BlogCategoryEditor extends AbstractEditor
{

    protected $_requiredFields = array('name', 'state');
    protected $_entityName = 'BlogCategory';

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Наименование'),
            'url' => array(Field::URL, 'Ссылка', 'name'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            )
        );
    }

}
