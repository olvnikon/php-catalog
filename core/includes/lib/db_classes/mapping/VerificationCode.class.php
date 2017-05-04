<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _VerificationCode
{

    public $entityName = 'VerificationCode';
    public $tableName = 'verification_code';
    public $mapping = array(
        'id' => 'id',
        'userId' => 'user_id',
        'vCode' => 'v_code',
        'state' => 'state',
        'createDate' => 'create_date',
        'activationDate' => 'activation_date',
        'endDate' => 'end_date'
    );
    public $types = array(
        'id' => 'TEXT',
        'userId' => 'TEXT',
        'vCode' => 'TEXT',
        'state' => 'TEXT',
        'createDate' => 'TEXT',
        'activationDate' => 'TEXT',
        'endDate' => 'TEXT'
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
