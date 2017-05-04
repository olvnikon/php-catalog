<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Category
{

    public $entityName = 'Category';
    public $tableName = 'product__category';
    public $mapping = array(
        'id' => 'id',
        'caption' => 'caption',
        'url' => 'url',
        'state' => 'state',
        'parentId' => 'parent_id',
        'featured' => 'featured',
        'sortOrder' => 'sort_order',
        'metaKeywords' => 'settings',
        'metaDescription' => 'settings',
        'image' => 'settings',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'user' => 'user'
    );
    public $types = array(
        'id' => 'TEXT',
        'caption' => 'TEXT',
        'url' => 'TEXT',
        'state' => 'TEXT',
        'parentId' => 'TEXT',
        'featured' => 'TEXT',
        'sortOrder' => 'TEXT',
        'metaKeywords' => 'JSON',
        'metaDescription' => 'JSON',
        'image' => 'JSON',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT'
    );
    public $order = 'ORDER BY sort_order ASC';
    public $group = '';

}
