<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _SubscriptionArticle
{

    public $entityName = 'SubscriptionArticle';
    public $tableName = 'subscription_article';
    public $mapping = array(
        'id' => 'id',
        'name' => 'name',
        'content' => 'content',
        'sendState' => 'send_state',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'user' => 'user'
    );
    public $types = array(
        'id' => 'TEXT',
        'name' => 'TEXT',
        'content' => 'TEXT',
        'sendState' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT'
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
