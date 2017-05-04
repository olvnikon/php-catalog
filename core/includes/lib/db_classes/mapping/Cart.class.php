<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Cart
{

    public $entityName = 'Cart';
    public $tableName = 'cart';
    public $mapping = array(
        'id' => 'id',
        'userId' => 'user_id',
        'cHash' => 'c_hash',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'products' => 'settings',
        'coupon' => 'settings'
    );
    public $types = array(
        'id' => 'TEXT',
        'userId' => 'TEXT',
        'cHash' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'products' => 'JSON',
        'coupon' => 'JSON'
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
