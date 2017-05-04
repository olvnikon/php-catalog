<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class ResourcesList extends AbstractList
{

    protected $_canRemove = TRUE;
    protected $_canEdit = FALSE;
    protected $_canAdd = TRUE;
    protected $_multySelect = FALSE;
    protected $_entityName = 'Resource';

    protected function _getFields()
    {
        return array(
            'url' => array(Field::IMAGE, 'url')
        );
    }

}
