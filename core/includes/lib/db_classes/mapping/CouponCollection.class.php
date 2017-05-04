<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _CouponCollection
{

    public $entityName = 'CouponCollection';
    public $tableName = 'coupon__collection';
    public $mapping = array(
        'id' => 'id',
        'name' => 'name',
        'startDate' => 'start_date',
        'stopDate' => 'stop_date',
        'state' => 'state',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'user' => 'user',
        'discount' => 'settings',
        'discountType' => 'settings',
        'couponCount' => 'settings'
    );
    public $types = array(
        'id' => 'TEXT',
        'name' => 'TEXT',
        'startDate' => 'TEXT',
        'stopDate' => 'TEXT',
        'state' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT',
        'discount' => 'JSON',
        'discountType' => 'JSON',
        'couponCount' => 'JSON'
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
