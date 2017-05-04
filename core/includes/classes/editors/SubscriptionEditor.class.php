<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class SubscriptionEditor extends AbstractAccountEditor
{

    protected $_requiredFields = array('subscription');

    protected function _getControls()
    {
        return array(
            'subscription' => array(
                Field::SELECTBOX, 'Подписка', self::_getSelectboxOnOffOptions()
            )
        );
    }

}
