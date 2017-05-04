<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class CouponsCollectionsList extends AbstractList
{

    protected $_canRemove = TRUE;
    protected $_canAdd = TRUE;
    protected $_multySelect = TRUE;
    protected $_sortable = FALSE;
    protected $_entityName = 'CouponCollection';

    protected function _getFields()
    {
        return array(
            'name' => array(Field::CAPTION, 'Наименование'),
            'startDate' => array(Field::CAPTION, 'Дата начала'),
            'stopDate' => array(Field::CAPTION, 'Дата конца'),
            'state' => array(
                Field::SELECT, 'Статус', self::_getSelectboxOnOffOptions()
            )
        );
    }

}
