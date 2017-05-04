<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _BlogCategory
{

    public $entityName = 'BlogCategory';
    public $tableName = 'blog__category';
    public $mapping = array(
        'id' => 'id',
        'name' => 'name',
        'url' => 'url',
        'state' => 'state',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'user' => 'user'
    );
    public $types = array(
        'id' => 'TEXT',
        'name' => 'TEXT',
        'url' => 'TEXT',
        'state' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT'
    );
    public $order = 'ORDER BY create_date DESC';
    public $group = '';

}
