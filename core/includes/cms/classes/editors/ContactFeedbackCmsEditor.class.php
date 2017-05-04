<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class ContactFeedbackCmsEditor extends AbstractEditor
{

    protected $_entityName = 'ContactFeedback';

    protected function _getControls()
    {
        return array(
            'name' => array(Field::CAPTION, 'Имя'),
            'email' => array(Field::CAPTION, 'Email'),
            'phone' => array(Field::CAPTION, 'Телефон'),
            'content' => array(Field::CAPTION, 'Описание отзыва')
        );
    }

}
