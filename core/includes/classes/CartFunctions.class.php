<?php

class CartFunctions
{

    /**
     *
     * @param Product $product
     * @param int $count
     * @return Cart
     */
    public static function addToCart(Product $product, $count = 1)
    {
        $cart = self::getCart();
        if (Cookie::isEmpty('cart_hash')) {
            Cookie::set('cart_hash', $cart->cHash);
        }

        self::_updateCartByProduct($cart, $product, $count);
        $cm = new CartManager();
        $cm->update($cart);
        return $cart;
    }

    /**
     *
     * @return Cart
     */
    public static function getCart()
    {
        $cm = new CartManager();
        $dbCart = self::_getCartFromDB($cm);
        return empty($dbCart)
            ? self::_createCart($cm)
            : $dbCart;
    }

    /**
     *
     * @param CartManager $cm
     * @return Cart|FALSE
     */
    private static function _getCartFromDB(CartManager $cm)
    {
        $hashCart = $cm->getAll('c_hash=:c_hash',
            array('c_hash' => Cookie::get('cart_hash')));
        if (!empty($hashCart)) {
            return $hashCart[0];
        }

        $user = Application::getLoggedUser();
        if (empty($user)) {
            return FALSE;
        }
        $dbCart = $cm->getAll(
            'user_id=:user_id', array('user_id' => $user->id)
        );

        return empty($dbCart)
            ? FALSE
            : $dbCart[0];
    }

    /**
     *
     * @param CartManager $cm
     * @return Cart
     */
    private static function _createCart(CartManager $cm)
    {
        $user = Application::getLoggedUser();
        $cart = new Cart();
        $cart->userId = empty($user)
            ? 0
            : $user->id;
        $cart->products = array();
        $cart->cHash = md5(uniqid());
        Cookie::set('cart_hash', $cart->cHash);
        return $cm->create($cart);
    }

    /**
     *
     * @param Cart $cart
     * @param Product $product
     * @param int $count
     * @return stdClass
     */
    private static function _updateCartByProduct(Cart $cart, Product $product,
        $count = 1)
    {
        if (empty($cart->products)) {
            $cart->products[] = self::_getCartProduct($product, $count);
            return;
        }

        foreach ($cart->products AS $cProduct) {
            if ($cProduct->id == $product->id) {
                $cProduct->count += $count;
                return;
            }
        }

        $cart->products[] = self::_getCartProduct($product, $count);
    }

    /**
     *
     * @param Product $product
     * @param int $count
     * @return \stdClass
     */
    private static function _getCartProduct(Product $product, $count = 1)
    {
        $productCart = new stdClass();
        $productCart->id = $product->id;
        $productCart->price = empty($product->newPrice)
            ? $product->price
            : $product->newPrice;
        $productCart->count = $count;
        return $productCart;
    }

    /**
     *
     * @param Cart $cart
     * @return float
     */
    public static function getCartPrice(Cart $cart)
    {
        if (empty($cart->products)) {
            return '0.00';
        }

        $price = 0;
        foreach ($cart->products AS $product) {
            $price += $product->price * $product->count;
        }

        return money_format('%i', $price);
    }

    /**
     *
     * @param Cart $cart
     * @return int
     */
    public static function getCartProductsCount(Cart $cart)
    {
        if (empty($cart->products)) {
            return 0;
        }

        $count = 0;
        foreach ($cart->products AS $product) {
            $count += $product->count;
        }

        return $count;
    }

    /**
     * @return void
     */
    public static function emptyCart($fullClean = FALSE)
    {
        $cart = self::getCart();
        $cart->products = array();
        if ($fullClean) {
            $cart->coupon = '';
        }

        $cm = new CartManager();
        $cm->update($cart);
    }

    /**
     *
     * @param Coupon $coupon
     * @return void
     */
    public static function applyCoupon(Coupon $coupon)
    {
        $cart = self::getCart();
        $cart->coupon = self::_getCartCoupon($coupon);

        $cm = new CartManager();
        $cm->update($cart);
    }

    /**
     *
     * @param Coupon $coupon
     * @return \stdClass
     */
    private static function _getCartCoupon(Coupon $coupon)
    {
        $collection = $coupon->getCollection();
        $cCoupon = new stdClass();
        $cCoupon->id = $coupon->id;
        $cCoupon->collectionId = $collection->id;
        $cCoupon->code = $coupon->code;
        $cCoupon->discount = $collection->discount;
        $cCoupon->discountType = $collection->discountType;
        return $cCoupon;
    }

    /**
     *
     * @param Cart $cart
     * @return float
     */
    public static function calcDiscount(Cart $cart)
    {
        $totalRaw = self::getCartPrice($cart);
        if (empty($cart->coupon->discount)) {
            return 0;
        }

        return $cart->coupon->discountType == CouponCollectionManager::DISCOUNT_TYPE_PERCENT
            ? floatval($totalRaw * $cart->coupon->discount / 100)
            : $cart->coupon->discount;
    }

    /**
     *
     * @param int $id
     * @return boolean
     */
    public static function removeFromCart($id)
    {
        $cart = self::getCart();
        if (empty($cart->products)) {
            return FALSE;
        }

        foreach ($cart->products AS $cid => $product) {
            if ($product->id == $id) {
                self::_processRemoveFromCart($cart, $cid);
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     *
     * @param Cart $cart
     * @param int $cid
     * @return void
     */
    private static function _processRemoveFromCart($cart, $cid)
    {
        unset($cart->products[$cid]);
        $cart->products = array_values($cart->products);
        $cm = new CartManager();
        $cm->update($cart);
    }

    /**
     *
     * @param boolean $includeDelivery
     * @return array
     */
    public static function getCartTotals($includeDelivery = FALSE)
    {
        $cart = self::getCart();
        $totalRaw = CartFunctions::getCartPrice($cart);
        $discount = CartFunctions::calcDiscount($cart);
        $total = floatval($totalRaw) - $discount + ($includeDelivery
                ? $GLOBALS['SettingManager']->getParamValue('Стоимость доставки курьером')
                : 0);
        return array(
            'totalRaw' => $totalRaw,
            'discount' => money_format('%i', $discount),
            'total' => $total < 0
                ? 0
                : money_format('%i', $total)
        );
    }

}
