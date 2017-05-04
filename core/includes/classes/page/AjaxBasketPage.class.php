<?php

/**
 * @author Никонов Владимир Андреевич
 */
class AjaxBasketPage extends AbstractSitePage
{

    /**
     * @return void
     */
    public function process()
    {
        if (Request::isEmpty('request')) {
            exit('0');
        }

        $this->_processRequest();
    }

    /**
     * @return void
     */
    private function _processRequest()
    {
        switch (Request::get('request')) {
            case 'add':
                $this->_processAddToCart();
            case 'refresh':
                $this->_refreshCart();
            case 'get_content':
                $this->_getContent();
            case 'remove_product':
                $this->_removeProduct();
            case 'get_totals':
                $this->_getTotals();
            case 'apply_coupon':
                $this->_applyCoupon();
            default:
                exit('0');
        }
    }

    /**
     * @return void
     */
    private function _processAddToCart()
    {
        if (Request::isEmpty('id')) {
            exit('0');
        }

        $pm = new ProductManager();
        $product = $pm->getById(Request::get('id'));
        if (empty($product)) {
            exit('0');
        }

        $count = Request::isEmpty('count')
            ? 1
            : Request::get('count');
        $this->_getResponse(CartFunctions::addToCart($product, $count));
    }

    /**
     *
     * @param Cart $cart
     * @return void
     */
    private function _getResponse(Cart $cart)
    {
        exit(
            json_encode(
                array(
                    'itemsCount' => CartFunctions::getCartProductsCount($cart),
                    'totalPrice' => CartFunctions::getCartPrice($cart)
                )
            )
        );
    }

    /**
     * @return void
     */
    private function _refreshCart()
    {
        $this->_getResponse(CartFunctions::getCart());
    }

    private function _getContent()
    {
        $cart = CartFunctions::getCart();
        exit(
            json_encode(
                array(
                    'total' => CartFunctions::getCartPrice($cart),
                    'products' => $this->_getCartProducts($cart)
                )
            )
        );
    }

    /**
     *
     * @param Cart $cart
     * @return \stdClass[]
     */
    private function _getCartProducts(Cart $cart)
    {
        if (empty($cart->products)) {
            return array();
        }

        $products = array();
        foreach ($cart->products AS $product) {
            $products[] = $this->_getCartProduct($product);
        }

        return $products;
    }

    /**
     *
     * @param \stdClass $product
     * @return \stdClass
     */
    private function _getCartProduct($product)
    {
        $patchedProduct = ProductBuilder::getItem($product->id);
        $cProduct = new stdClass();
        $cProduct->id = $product->id;
        $cProduct->caption = $patchedProduct->caption;
        $cProduct->count = $product->count;
        $cProduct->price = $product->price;
        $cProduct->image = $patchedProduct->firstImage;
        return $cProduct;
    }

    /**
     * @return void
     */
    private function _removeProduct()
    {
        CartFunctions::removeFromCart(Request::get('id'));
        $this->_refreshCart();
    }

    /**
     * @return void
     */
    private function _getTotals()
    {
        exit(
            json_encode(
                CartFunctions::getCartTotals(
                    PurchaseManager::TYPE_COURIER == Request::get('delivery')
                )
            )
        );
    }

    /**
     * @return void
     */
    private function _applyCoupon()
    {
        $cm = new CouponManager();
        $coupon = $cm->getAvailableCouponByCode(
            Request::get('coupon_code')
        );
        if (empty($coupon)) {
            exit('0');
        }

        CartFunctions::applyCoupon($coupon);
        exit('1');
    }

    public function show()
    {

    }

}
