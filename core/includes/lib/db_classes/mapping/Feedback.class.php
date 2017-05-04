<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Feedback
{

    public $entityName = 'Feedback';
    public $tableName = 'feedback';
    public $mapping = array(
        'id' => 'id',
        'fio' => 'fio',
        'email' => 'email',
        'state' => 'state',
        'productId' => 'product_id',
        'content' => 'content',
        'readState' => 'read_state',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'user' => 'user',
    );
    public $types = array(
        'id' => 'TEXT',
        'fio' => 'TEXT',
        'email' => 'TEXT',
        'state' => 'TEXT',
        'productId' => 'TEXT',
        'content' => 'TEXT',
        'readState' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'user' => 'TEXT',
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
