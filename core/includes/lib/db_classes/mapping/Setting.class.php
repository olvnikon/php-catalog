<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Setting
{

    public $entityName = 'Setting';
    public $tableName = 'setting';
    public $mapping = array(
        'id' => 'id',
        'paramName' => 'param_name',
        'paramValue' => 'param_value',
        'modifyDate' => 'modify_date',
        'type' => 'type',
        'user' => 'user',
        'sortOrder' => 'sort_order'
    );
    public $types = array(
        'id' => 'TEXT',
        'paramName' => 'TEXT',
        'paramValue' => 'JSON',
        'modifyDate' => 'TEXT',
        'type' => 'TEXT',
        'user' => 'TEXT',
        'sortOrder' => 'TEXT'
    );
    public $order = 'ORDER BY sort_order ASC';
    public $group = '';

}
