<?php

/**
 * @author Никонов Владимир Андреевич
 */
class _Product
{

    public $entityName = 'Product';
    public $tableName = 'product';
    public $mapping = array(
        'id' => 'id',
        'caption' => 'caption',
        'url' => 'url',
        'description' => 'description',
        'state' => 'state',
        'category' => 'category',
        'isSpecial' => 'is_special',
        'price' => 'price',
        'newPrice' => 'new_price',
        'priceFor' => 'settings',
        'nominalWeight' => 'settings',
        'nominalCount' => 'settings',
        'containInfo' => 'settings',
        'metaKeywords' => 'settings',
        'metaDescription' => 'settings',
        'createDate' => 'create_date',
        'modifyDate' => 'modify_date',
        'sortOrder' => 'sort_order',
        'user' => 'user'
    );
    public $types = array(
        'id' => 'TEXT',
        'caption' => 'TEXT',
        'url' => 'TEXT',
        'description' => 'TEXT',
        'state' => 'TEXT',
        'category' => 'TEXT',
        'isSpecial' => 'TEXT',
        'price' => 'TEXT',
        'newPrice' => 'TEXT',
        'priceFor' => 'JSON',
        'nominalWeight' => 'JSON',
        'nominalCount' => 'JSON',
        'containInfo' => 'JSON',
        'metaKeywords' => 'JSON',
        'metaDescription' => 'JSON',
        'createDate' => 'TEXT',
        'modifyDate' => 'TEXT',
        'sortOrder' => 'TEXT',
        'user' => 'TEXT'
    );
    public $order = 'ORDER BY sort_order,id DESC';
    public $group = '';

}
