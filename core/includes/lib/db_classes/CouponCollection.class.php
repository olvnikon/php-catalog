<?php

/**
 * @author Никонов Владимир Андреевич
 */
class CouponCollection extends AbstractEntity
{

    public $id;
    public $name;
    public $startDate;
    public $stopDate;
    public $state;
    public $createDate;
    public $modifyDate;
    public $user;
    public $discount;
    public $discountType;
    public $couponCount;

}

class CouponCollectionManager extends AbstractManager
{

    const DISCOUNT_TYPE_PERCENT = 1;
    const DISCOUNT_TYPE_RUB = 2;

    /**
     *
     * @param int $discountType
     * @return string
     */
    public static function getDiscountTypeText($discountType)
    {
        switch ($discountType) {
            case self::DISCOUNT_TYPE_RUB:
                return 'руб.';
            case self::DISCOUNT_TYPE_PERCENT:
            default:
                return '%';
        }
    }

    /**
     *
     * @param CouponCollection $collection
     * @return CouponCollection
     */
    public function create(AbstractEntity $collection)
    {
        require_once 'code/Generator.php';

        $newCollection = parent::create($collection);
        $cm = new CouponManager();
        for ($i = 0; $i < intval($newCollection->couponCount); $i++) {
            $coupon = new Coupon();
            $coupon->code = Code\Generator::getRandomKey($newCollection->id);
            $coupon->collectionId = $newCollection->id;
            $cm->create($coupon);
        }

        return $newCollection;
    }

    /**
     *
     * @param type $id
     */
    public function delete($id)
    {
        $cm = new CouponManager();
        $coupons = $cm->getAll(
            'is_used IS NULL AND collection_id=:collection_id',
            array('collection_id' => $id)
        );
        foreach ($coupons AS $coupon) {
            $cm->delete($coupon->id);
        }

        return parent::delete($id); ;
    }

}
