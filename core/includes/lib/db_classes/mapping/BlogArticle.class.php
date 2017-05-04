<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _BlogArticle
{

    public $entityName = 'BlogArticle';
    public $tableName = 'blog__article';
    public $mapping = array(
        'id' => 'id',
        'name' => 'name',
        'url' => 'url',
        'image' => 'image',
        'content' => 'content',
        'state' => 'state',
        'categoryId' => 'category_id',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'user' => 'user',
        'keywords' => 'settings',
        'description' => 'settings'
    );
    public $types = array(
        'id' => 'TEXT',
        'name' => 'TEXT',
        'url' => 'TEXT',
        'image' => 'TEXT',
        'content' => 'TEXT',
        'state' => 'TEXT',
        'categoryId' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT',
        'keywords' => 'JSON',
        'description' => 'JSON'
    );
    public $order = 'ORDER BY create_date DESC';
    public $group = '';

}
