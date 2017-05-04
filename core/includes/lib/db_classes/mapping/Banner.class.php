<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Banner
{

    public $entityName = 'Banner';
    public $tableName = 'product__banner';
    public $mapping = array(
        'id' => 'id',
        'content' => 'content',
        'name' => 'name',
        'state' => 'state',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'user' => 'user'
    );
    public $types = array(
        'id' => 'TEXT',
        'content' => 'TEXT',
        'name' => 'TEXT',
        'state' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT'
    );
    public $order = 'ORDER BY id ASC';
    public $group = '';

}
