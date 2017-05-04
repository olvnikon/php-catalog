<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class StaticPagesList extends AbstractList
{

    protected $_entityName = 'StaticPage';
    protected $_showFilter = TRUE;

    protected function _getFields()
    {
        return array(
            'name' => array(Field::CAPTION, 'Название', TRUE)
        );
    }

}
