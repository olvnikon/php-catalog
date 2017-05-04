<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class FeedbacksList extends AbstractList
{

    protected $_canRemove = TRUE;
    protected $_canAdd = FALSE;
    protected $_multySelect = TRUE;
    protected $_entityName = 'Feedback';
    protected $_showFilter = TRUE;

    protected function _getFields()
    {
        return array(
            'readState' => array(
                Field::IMAGE_STATE, 'Статус',
                array(0 => 'Новый отзыв', 1 => 'Проверен'), TRUE
            ),
            'fio' => array(Field::CAPTION, 'ФИО', TRUE),
            'email' => array(Field::CAPTION, 'Email', TRUE),
            'state' => array(
                Field::SELECT, 'Статус', self::_getSelectboxOnOffOptions(), TRUE
            ),
            'createDate' => array(Field::CAPTION, 'Дата добавления')
        );
    }

}
