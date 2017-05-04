<?php

class CartView
{

    /**
     *
     * @param Template $tpl
     * @param Cart $cart
     * @return void
     */
    public static function processTpl(Template $tpl, Cart $cart)
    {
        self::_parsePriceOptions($tpl, $cart);
        self::_parseStaticContent($tpl);

        if (empty($cart->products)) {
            return;
        }

        foreach ($cart->products AS $cProduct) {
            ProductView::processFullTemplate($tpl,
                ProductBuilder::getItem($cProduct->id));
            $tpl->setVar('Product-InCart', $cProduct->count);
            $tpl->setVar('PriceTotal',
                money_format('%i', $cProduct->price * $cProduct->count));
            $tpl->parseB2V('Products', 'CART-PRODUCT', TRUE);
        }
    }

    /**
     *
     * @param Template $tpl
     * @param Cart $cart
     * @return void
     */
    private static function _parsePriceOptions(Template $tpl, Cart $cart)
    {
        $totals = CartFunctions::getCartTotals();
        $tpl->setVar('Cart-TotalRaw', money_format('%i', $totals['totalRaw']));
        $tpl->setVar('Cart-Discount', money_format('%i', $totals['discount']));
        $tpl->setVar('Cart-Total', money_format('%i', $totals['total']));

        if (!empty($cart->coupon->code)) {
            $tpl->setVar('Coupon-Code', $cart->coupon->code);
        }
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private static function _parseStaticContent(Template $tpl)
    {
        $spm = new StaticPageManager();
        $billText = $spm->getById(PAGE_TYPE_CART_BILL);
        $tpl->setVar('Cart-Bill', $billText->content);
    }

}
