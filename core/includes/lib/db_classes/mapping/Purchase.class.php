<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Purchase
{

    public $entityName = 'Purchase';
    public $tableName = 'purchase';
    public $mapping = array(
        'id' => 'id',
        'userId' => 'user_id',
        'state' => 'state',
        'total' => 'total',
        'comment' => 'comment',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'totalPure' => 'settings',
        'discount' => 'settings',
        'contactPhone' => 'settings',
        'deliveryType' => 'settings',
        'paymentType' => 'settings',
        'products' => 'settings',
        'couponId' => 'settings'
    );
    public $types = array(
        'id' => 'TEXT',
        'userId' => 'TEXT',
        'state' => 'TEXT',
        'total' => 'TEXT',
        'comment' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'totalPure' => 'JSON',
        'discount' => 'JSON',
        'contactPhone' => 'JSON',
        'deliveryType' => 'JSON',
        'paymentType' => 'JSON',
        'products' => 'JSON',
        'couponId' => 'JSON'
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
