<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Purchase extends AbstractEntity
{

    public $id;
    public $userId;
    public $state;
    public $total;
    public $comment;
    public $totalPure;
    public $discount;
    public $contactPhone;
    public $deliveryType;
    public $paymentType;
    public $createDate;
    public $modifyDate;
    public $products;
    public $couponId;

}

class PurchaseManager extends AbstractManager
{

    const TYPE_COURIER = 1;
    const TYPE_PICKUP = 2;
    const STATE_UNPAID = 0;
    const STATE_PAID = 1;
    const PAYMENT_CASH = 1;
    const PAYMENT_ONLINE = 2;

}
