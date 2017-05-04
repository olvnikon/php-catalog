<?php

/**
 * @author Никонов Владимир Андреевич
 */
class PaymentErrorPage extends AbstractStaticPage
{

    /**
     *
     * @return int
     */
    protected function _getPageId()
    {
        return PAGE_TYPE_PAYMENT_ERROR;
    }

}
