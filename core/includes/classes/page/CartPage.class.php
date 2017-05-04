<?php

/**
 * @author Никонов Владимир Андреевич
 */
class CartPage extends AbstractSitePage
{

    public function process()
    {
        if (Request::issetParam('refresh_cart')) {
            $this->_processRefreshCart();
            Request::goToLocalPage('/cart');
        }
    }

    /**
     *
     * @return void
     */
    private function _processRefreshCart()
    {
        $productIds = Request::get('product_id');
        $productCount = Request::get('product_count');
        if (!$this->_isValidInputData($productIds, $productCount)) {
            return;
        }

        $pm = new ProductManager();
        CartFunctions::emptyCart();
        foreach ($productIds AS $ind => $productId) {
            if (empty($productCount[$ind])) {
                continue;
            }

            $product = $pm->getById($productId);
            if (empty($product)) {
                continue;
            }

            CartFunctions::addToCart($product, $productCount[$ind]);
        }

        $cart = CartFunctions::getCart();
        if (!empty($cart->coupon->code)) {
            $this->_processCoupon($cart->coupon->code);
        }
    }

    /**
     *
     * @param int[] $productIds
     * @param int[] $productCount
     * @return boolean
     */
    private function _isValidInputData($productIds, $productCount)
    {
        return !empty($productIds) && is_array($productIds)
            && !empty($productCount) && is_array($productCount)
            && count($productIds) == count($productCount);
    }

    /**
     *
     * @param string $code
     * @return void
     */
    private function _processCoupon($code)
    {
        $cm = new CouponManager();
        $coupon = $cm->getAvailableCouponByCode($code);
        if (empty($coupon)) {
            return;
        }

        CartFunctions::applyCoupon($coupon);
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $tpl = new Template(CFG_PATH_TPL . 'view/cart_full.html');
        CartView::processTpl($tpl, CartFunctions::getCart());
        $this->_tpl->setVar('Page-Content', $tpl->fillTemplate());
    }

}
