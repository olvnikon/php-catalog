<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Resource {

    public $entityName = 'Resource';
    public $tableName = 'resource';
    public $mapping = array(
        'id' => 'id',
        'url' => 'url',
        'createDate' => 'create_date'
    );
    public $types = array(
        'id' => 'TEXT',
        'url' => 'TEXT',
        'createDate' => 'TEXT'
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
