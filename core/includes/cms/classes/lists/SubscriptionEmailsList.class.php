<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class SubscriptionEmailsList extends AbstractList
{

    protected $_showFilter = TRUE;
    protected $_multySelect = TRUE;
    protected $_canRemove = TRUE;
    protected $_canAdd = TRUE;
    protected $_entityName = 'SubscriptionEmail';

    protected function _getFields()
    {
        return array(
            'email' => array(Field::CAPTION, 'Email', TRUE)
        );
    }

}
