<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _User
{

    public $entityName = 'User';
    public $tableName = 'user';
    public $mapping = array(
        'id' => 'id',
        'email' => 'email',
        'password' => 'password',
        'nePasswd' => 'ne_passwd',
        'type' => 'type',
        'phone' => 'phone',
        'name' => 'name',
        'lastName' => 'last_name',
        'town' => 'town',
        'address' => 'address',
        'state' => 'state',
        'subscription' => 'subscription',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'logonDate' => 'logon_date',
        'priveleges' => 'settings',
        'company' => 'settings',
        'companyPhone' => 'settings',
        'fax' => 'settings',
        'postcode' => 'settings'
    );
    public $types = array(
        'id' => 'TEXT',
        'email' => 'TEXT',
        'password' => 'TEXT',
        'nePasswd' => 'TEXT',
        'type' => 'TEXT',
        'phone' => 'TEXT',
        'name' => 'TEXT',
        'lastName' => 'TEXT',
        'town' => 'TEXT',
        'address' => 'TEXT',
        'state' => 'TEXT',
        'subscription' => 'TEXT',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'logonDate' => 'TEXT',
        'priveleges' => 'JSON',
        'company' => 'JSON',
        'companyPhone' => 'JSON',
        'fax' => 'JSON',
        'postcode' => 'JSON'
    );
    public $order = 'ORDER BY id DESC';
    public $group = '';

}
