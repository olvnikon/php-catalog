<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _ContactFeedback
{

    public $entityName = 'ContactFeedback';
    public $tableName = 'feedback__contact';
    public $mapping = array(
        'id' => 'id',
        'name' => 'name',
        'email' => 'email',
        'phone' => 'phone',
        'content' => 'content',
        'readState' => 'read_state',
        'createDate' => 'create_date'
    );
    public $types = array(
        'id' => 'TEXT',
        'name' => 'TEXT',
        'email' => 'TEXT',
        'phone' => 'TEXT',
        'content' => 'TEXT',
        'readState' => 'TEXT',
        'createDate' => 'TEXT'
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
