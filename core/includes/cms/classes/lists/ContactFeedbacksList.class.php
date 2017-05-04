<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class ContactFeedbacksList extends AbstractList
{

    protected $_canRemove = TRUE;
    protected $_canAdd = FALSE;
    protected $_multySelect = TRUE;
    protected $_entityName = 'ContactFeedback';
    protected $_showFilter = TRUE;

    protected function _getFields()
    {
        return array(
            'readState' => array(
                Field::IMAGE_STATE, 'Статус',
                array(0 => 'Новый отзыв', 1 => 'Проверен'), TRUE
            ),
            'name' => array(Field::CAPTION, 'Имя', TRUE),
            'email' => array(Field::CAPTION, 'Email', TRUE),
            'phone' => array(Field::CAPTION, 'Телефон', TRUE),
            'createDate' => array(Field::CAPTION, 'Дата добавления')
        );
    }

}
