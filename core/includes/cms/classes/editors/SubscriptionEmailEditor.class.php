<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class SubscriptionEmailEditor extends AbstractEditor
{

    protected $_requiredFields = array('email');
    protected $_entityName = 'SubscriptionEmail';

    protected function _getControls()
    {
        return array(
            'email' => array(Field::TEXT, 'Email')
        );
    }

}
