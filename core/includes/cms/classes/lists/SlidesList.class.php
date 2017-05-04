<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class SlidesList extends AbstractList
{

    protected $_entityName = 'Slide';
    protected $_sortable = TRUE;
    protected $_canRemove = TRUE;
    protected $_canEdit = FALSE;
    protected $_canAdd = TRUE;
    protected $_multySelect = TRUE;

    protected function _getFields()
    {
        return array(
            'url' => array(Field::IMAGE, 'Ссылка')
        );
    }

}
