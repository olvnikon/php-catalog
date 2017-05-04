<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Page
{

    public $entityName = 'Page';
    public $tableName = 'page';
    public $mapping = array(
        'id' => 'id',
        'name' => 'name',
        'class' => 'class'
    );
    public $types = array(
        'id' => 'TEXT',
        'name' => 'TEXT',
        'class' => 'TEXT'
    );
    public $order = 'ORDER BY id ASC';
    public $group = '';

}
