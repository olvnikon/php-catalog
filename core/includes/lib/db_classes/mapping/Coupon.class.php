<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Coupon
{

    public $entityName = 'Coupon';
    public $tableName = 'coupon';
    public $mapping = array(
        'id' => 'id',
        'collectionId' => 'collection_id',
        'isUsed' => 'is_used',
        'code' => 'c_code',
        'userId' => 'user_id',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date'
    );
    public $types = array(
        'id' => 'TEXT',
        'collectionId' => 'TEXT',
        'isUsed' => 'TEXT',
        'code' => 'TEXT',
        'userId' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT'
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
