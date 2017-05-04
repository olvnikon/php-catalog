<?php

/**
 * @author Никонов Владимир Андреевич
 */
class PurchasePage extends AbstractSitePage
{

    public function process()
    {
        $user = Application::getLoggedUser();
        if (empty($user)) {
            Request::goToLocalPage('/login');
        }
        if (!Request::isEmpty('purchase_phone')) {
            $this->_processPurchase();
        }
    }

    /**
     * @return void
     */
    private function _processPurchase()
    {
        $cart = CartFunctions::getCart();
        if (empty($cart->products)) {
            Request::goToLocalPage('/purchase?error=1');
        }
        $purchase = $this->_createNewPurchase($cart);
        $this->_updateCoupon($purchase);
        CartFunctions::emptyCart(TRUE);

        if ($purchase->paymentType == PurchaseManager::PAYMENT_ONLINE) {
            Request::goToLocalPage('/payment_proceed?purchase=' . $purchase->id);
        }
        Request::goToLocalPage('/purchase?success=1');
    }

    /**
     *
     * @param Cart $cart
     * @return Purchase
     */
    private function _createNewPurchase(Cart $cart)
    {
        $purchase = new Purchase();
        $purchase->comment = strip_tags(Request::get('purchase_comment'));
        $purchase->contactPhone = strip_tags(Request::get('purchase_phone'));
        $purchase->deliveryType = Request::get('delivery_type');
        $purchase->paymentType = Request::get('payment_type');
        $purchase->couponId = empty($cart->coupon->id)
            ? 0
            : $cart->coupon->id;
        $this->_processPurchaseMoney($purchase);
        $purchase->userId = Application::getLoggedUser()->id;
        $purchase->state = PurchaseManager::STATE_UNPAID;
        $purchase->products = $cart->products;
        $pm = new PurchaseManager();
        return $pm->create($purchase);
    }

    /**
     *
     * @param Purchase $purchase
     * @return void
     */
    private function _processPurchaseMoney(Purchase $purchase)
    {
        $totals = CartFunctions::getCartTotals(
                $purchase->deliveryType == PurchaseManager::TYPE_COURIER
        );
        $purchase->totalPure = $totals['totalRaw'];
        $purchase->discount = $totals['discount'];
        $purchase->total = $totals['total'];
    }

    /**
     *
     * @param Purchase $purchase
     * @return void
     */
    private function _updateCoupon(Purchase $purchase)
    {
        $cm = new CouponManager();
        $coupon = $cm->getById($purchase->couponId);
        if (empty($coupon)) {
            return;
        }

        $coupon->isUsed = 1;
        $coupon->userId = Application::getLoggedUser()->id;
        $cm->update($coupon);
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $tpl = new Template(CFG_PATH_TPL . 'cart_step_2.html');

        $this->_parseUserOptions($tpl);
        $this->_parsePageSettings($tpl);
        CartView::processTpl($tpl, CartFunctions::getCart());

        $this->_tpl->setVar('Page-Content', $tpl->fillTemplate());
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseUserOptions(Template $tpl)
    {
        $tpl->setVar('User-Phone', Application::getLoggedUser()->phone);
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parsePageSettings(Template $tpl)
    {
        $tpl->setVar('General-DeliveryCost',
            number_format(
                $GLOBALS['SettingManager']->getParamValue('Стоимость доставки курьером'),
                2));
        if (!Request::isEmpty('success')) {
            $tpl->parseB2V('Message', 'SUCCESS');
        } elseif (!Request::isEmpty('error')) {
            $tpl->parseB2V('Message', 'ERROR');
        }
    }

}
