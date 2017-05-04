<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Coupon extends AbstractEntity
{

    public $id;
    public $collectionId;
    public $isUsed;
    public $code;
    public $userId;
    public $createDate;
    public $modifyDate;

    /**
     *
     * @return CouponCollection
     */
    public function getCollection()
    {
        $ccm = new CouponCollectionManager();
        return $ccm->getById($this->collectionId);
    }

}

class CouponManager extends AbstractManager
{

    /**
     *
     * @param string $code
     * @return Coupon|FALSE
     */
    public function getAvailableCouponByCode($code)
    {
        $coupon = $this->_getCouponForUserByCode($code);
        if (empty($coupon)) {
            return FALSE;
        }

        $ccm = new CouponCollectionManager();
        $collection = $ccm->getAll(
            'id=:id AND state=1 AND start_date < NOW() AND stop_date > NOW()',
            array('id' => $coupon[0]->collectionId)
        );

        return empty($collection)
            ? FALSE
            : $coupon[0];
    }

    /**
     *
     * @param string $code
     * @return Coupon
     */
    private function _getCouponForUserByCode($code)
    {
        return $this->getAll(
                'c_code=:c_code AND is_used IS NULL
            AND (user_id IS NULL OR user_id=:user_id)',
                array('c_code' => $code,
                'user_id' => Application::getLoggedUser()
                    ? Application::getLoggedUser()->id
                    : 0)
        );
    }

}
