<?php

class PaymentProceedPage extends AbstractSitePage
{

    /**
     *
     * @var Purchase
     */
    private $_purchase;

    /**
     *
     * @var PurchaseManager
     */
    private $_manager;

    /**
     * Инициализация шаблона
     *
     * @return void
     */
    protected function _initTpl()
    {
        $this->_tpl = new Template(CFG_PATH_TPL . 'payment_proceed.html');
    }

    public function process()
    {
        $this->_manager = new PurchaseManager();
        $this->_purchase = $this->_manager->getById(Request::get('purchase'));
        if (empty($this->_purchase)
            || !Application::getLoggedUser()
            || Application::getLoggedUser()->id != $this->_purchase->userId) {
            Request::goToLocalPage('/');
        }
    }

    protected function _buildContent()
    {
        require_once 'payment/API.php';

        $this->_tpl->setVar('MerchantLogin', ROBOKASSA_LOGIN);
        $this->_tpl->setVar('OutSum',
            money_format('%i', $this->_purchase->total));
        $this->_tpl->setVar('InvoiceID', $this->_purchase->id);
        $this->_tpl->setVar('Description', 'Оплата заказа в магазине');
        $this->_tpl->setVar('SignatureValue',
            Payment\API::getCRCSend($this->_purchase));

        $this->_manager->update($this->_purchase);
    }

}
