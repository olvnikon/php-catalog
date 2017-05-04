<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _SubscriptionEmail
{

    public $entityName = 'SubscriptionEmail';
    public $tableName = 'subscription_email';
    public $mapping = array(
        'id' => 'id',
        'email' => 'email'
    );
    public $types = array(
        'id' => 'TEXT',
        'email' => 'TEXT'
    );
    public $order = 'ORDER BY id ASC';
    public $group = '';

}
