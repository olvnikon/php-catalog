<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _ProductPhoto
{

    public $entityName = 'ProductPhoto';
    public $tableName = 'product__photo';
    public $mapping = array(
        'id' => 'id',
        'fileName' => 'file_name',
        'productId' => 'product_id',
        'sortOrder' => 'sort_order',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'user' => 'user'
    );
    public $types = array(
        'id' => 'TEXT',
        'fileName' => 'TEXT',
        'productId' => 'TEXT',
        'sortOrder' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT'
    );
    public $order = 'ORDER BY product_id,sort_order ASC';
    public $group = '';

}
