<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _StaticPage
{

    public $entityName = 'StaticPage';
    public $tableName = 'static_page';
    public $mapping = array(
        'id' => 'id',
        'name' => 'name',
        'content' => 'content',
        'keywords' => 'settings',
        'description' => 'settings',
        'modifyDate' => 'modify_date',
        'user' => 'user'
    );
    public $types = array(
        'id' => 'TEXT',
        'name' => 'TEXT',
        'content' => 'TEXT',
        'keywords' => 'JSON',
        'description' => 'JSON',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT'
    );
    public $order = 'ORDER BY id ASC';
    public $group = '';

}
