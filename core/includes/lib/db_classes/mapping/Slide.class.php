<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Slide
{

    public $entityName = 'Slide';
    public $tableName = 'slide';
    public $mapping = array(
        'id' => 'id',
        'url' => 'url',
        'sortOrder' => 'sort_order'
    );
    public $types = array(
        'id' => 'TEXT',
        'url' => 'TEXT',
        'sortOrder' => 'TEXT'
    );
    public $order = 'ORDER BY sort_order ASC';
    public $group = '';

}
