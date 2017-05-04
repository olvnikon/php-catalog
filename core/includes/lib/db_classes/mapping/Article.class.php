<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Article
{

    public $entityName = 'Article';
    public $tableName = 'article';
    public $mapping = array(
        'id' => 'id',
        'name' => 'name',
        'content' => 'content',
        'state' => 'state',
        'parentId' => 'parent_id',
        'sortOrder' => 'sort_order',
        'keywords' => 'settings',
        'description' => 'settings',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'user' => 'user'
    );
    public $types = array(
        'id' => 'TEXT',
        'name' => 'TEXT',
        'content' => 'TEXT',
        'state' => 'TEXT',
        'parentId' => 'TEXT',
        'sortOrder' => 'TEXT',
        'keywords' => 'JSON',
        'description' => 'JSON',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT'
    );
    public $order = 'ORDER BY sort_order ASC';
    public $group = '';

}
