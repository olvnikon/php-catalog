<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class BannersList extends AbstractList
{

    protected $_canRemove = TRUE;
    protected $_canAdd = TRUE;
    protected $_multySelect = TRUE;
    protected $_showFilter = TRUE;
    protected $_entityName = 'Banner';

    protected function _getFields()
    {
        return array(
            'name' => array(Field::CAPTION, 'Имя', TRUE),
            'state' => array(
                Field::SELECT, 'Статус', self::_getSelectboxOnOffOptions(), TRUE
            )
        );
    }

}
