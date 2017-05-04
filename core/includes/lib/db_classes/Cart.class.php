<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Cart extends AbstractEntity
{

    public $id;
    public $userId;
    public $cHash;
    public $createDate;
    public $modifyDate;
    public $products;
    public $coupon;

}

class CartManager extends AbstractManager
{

}
