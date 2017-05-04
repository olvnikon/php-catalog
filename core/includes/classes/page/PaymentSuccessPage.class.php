<?php

/**
 * @author Никонов Владимир Андреевич
 */
class PaymentSuccessPage extends AbstractStaticPage
{

    public function process()
    {
        if (Request::isEmpty('SignatureValue')) {
            Request::goToLocalPage('/');
        }

        require_once 'payment/API.php';
        $paymentSuccess = Payment\API::checkPayment(
                Request::get('OutSum'), Request::get('InvId'),
                Request::get('SignatureValue')
        );

        if (!$paymentSuccess) {
            Request::goToLocalPage('/payment_error');
        }

        $pm = new PurchaseManager();
        $purchase = $pm->getById(Request::get('InvId'));
        $purchase->state = PurchaseManager::STATE_PAID;
        $pm->update($purchase);
    }

    /**
     *
     * @return int
     */
    protected function _getPageId()
    {
        return PAGE_TYPE_PAYMENT_SUCCESS;
    }

}
