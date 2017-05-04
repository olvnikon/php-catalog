<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Product extends AbstractEntity
{

    public $id;
    public $caption;
    public $url;
    public $description;
    public $state;
    public $category;
    public $isSpecial;
    public $nominalWeight;
    public $nominalCount;
    public $price;
    public $newPrice;
    public $priceFor;
    public $containInfo;
    public $metaKeywords;
    public $metaDescription;
    public $createDate;
    public $modifyDate;
    public $sortOrder;
    public $user;

}

class ProductManager extends AbstractManager
{
    const PRICE_FOR_KG = 1;
    const PRICE_FOR_AMOUNT = 2;

    public static function getPriceForCaption(Product $product) {
        switch ($product->priceFor) {
            case self::PRICE_FOR_KG:
                return 'кг';
            case self::PRICE_FOR_AMOUNT:
                return 'шт';
            default:
                return '';
        }
    }
}
