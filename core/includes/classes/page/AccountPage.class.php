<?php

/**
 * @author Никонов Владимир Андреевич
 */
class AccountPage extends AbstractSitePage
{

    /**
     *
     * @var string
     */
    private $_action;

    /**
     *
     * @var string[]
     */
    private $_allowedActions = array(
        'index', 'edit', 'address', 'orders', 'subscription',
        'logout', 'password'
    );

    public function __construct()
    {
        $this->_initAction();
        parent::__construct();
    }

    /**
     * @return void
     */
    private function _initAction()
    {
        $action = Request::get('action');
        if (!$this->_isAllowedAction($action)) {
            Request::goToLocalPage('/account/index/');
        }

        $this->_action = Request::get('action');
    }

    /**
     *
     * @param string $action
     * @return boolean
     */
    private function _isAllowedAction($action)
    {
        return in_array($action, $this->_allowedActions);
    }

    /**
     * @return void
     */
    public function process()
    {
        switch ($this->_action) {
            case 'logout':
                $this->_processIndex();
                break;
            case 'edit':
                $this->_processEdit();
                break;
            case 'password':
                $this->_processPassword();
                break;
            case 'address':
                $this->_processAddress();
                break;
            case 'subscription':
                $this->_processSubscription();
                break;
        }
    }

    /**
     * @return void
     */
    private function _processIndex()
    {
        Application::getInstance()->security->logOut();
        Request::goToLocalPage('/');
    }

    /**
     * @return void
     */
    private function _processEdit()
    {
        if (Request::isEmpty('editorSubmit')) {
            return;
        }
        $editor = new AccountEditor(clone Application::getLoggedUser());
        $editor->process();
    }

    /**
     * @return void
     */
    private function _processPassword()
    {
        if (Request::isEmpty('editorSubmit')) {
            return;
        }
        $editor = new PasswordEditor(clone Application::getLoggedUser());
        $editor->process();
    }

    /**
     * @return void
     */
    private function _processAddress()
    {
        if (Request::isEmpty('editorSubmit')) {
            return;
        }
        $editor = new AddressEditor(clone Application::getLoggedUser());
        $editor->process();
    }

    /**
     * @return void
     */
    private function _processSubscription()
    {
        if (Request::isEmpty('editorSubmit')) {
            return;
        }
        $editor = new SubscriptionEditor(clone Application::getLoggedUser());
        $editor->process();
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $generalTpl = new Template(CFG_PATH_TPL . 'account_general.html');
        $tpl = new Template(CFG_PATH_TPL . 'account_' . $this->_action . '.html');
        $this->_parseLeftNavigation($generalTpl);
        $this->_parsePageContentByAction($tpl);
        $generalTpl->setVar('PageContent', $tpl->fillTemplate());
        $this->_tpl->setVar('Page-Content', $generalTpl->fillTemplate());
    }

    /**
     *
     * @param Template $generalTpl
     * @return void
     */
    private function _parseLeftNavigation(Template $generalTpl)
    {
        foreach ($this->_getNavigationElements() AS $link => $caption) {
            $generalTpl->setVar('ElementUrl', $link);
            $generalTpl->setVar('ElementCaption', $caption);
            $generalTpl->setVar('ElementClass', '');
            if ($_SERVER['REQUEST_URI'] == $link) {
                $generalTpl->parseB2V('ElementClass', 'ACTIVE-CLASS');
            }
            $generalTpl->parseB2V('Left-Navigation', 'ELEMENT', TRUE);
        }
    }

    /**
     *
     * @return array
     */
    private function _getNavigationElements()
    {
        return array(
            '/account/index/' => 'Панель управления',
            '/account/edit/' => 'Данные учётной записи',
            '/account/password/' => 'Пароль',
            '/account/address/' => 'Адресная книга',
            '/account/orders/' => 'Мои заказы',
            '/account/subscription/' => 'Подписки на новостную рассылку'
        );
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parsePageContentByAction(Template $tpl)
    {
        switch ($this->_action) {
            case 'index':
                $this->_parseIndex($tpl);
                break;
            case 'edit':
                $this->_parseAccountDataEditor($tpl);
                break;
            case 'password':
                $this->_parseAccountPasswordEditor($tpl);
                break;
            case 'address':
                $this->_parseAccountAddressEditor($tpl);
                break;
            case 'subscription':
                $this->_parseAccountSubscriptionEditor($tpl);
                break;
            case 'orders':
                $this->_parseAccountOrders($tpl);
                break;
        }
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseIndex(Template $tpl)
    {
        UserView::processTpl($tpl, Application::getLoggedUser());
        $this->_parseAccountGreeting($tpl);
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseAccountGreeting(Template $tpl)
    {
        $spm = new StaticPageManager();
        $page = $spm->getById(PAGE_TYPE_ACCOUNT_GREETING);
        $tpl->setVar('Account-Greeting', $page->content);
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseAccountDataEditor(Template $tpl)
    {
        $editor = new AccountEditor(clone Application::getLoggedUser());
        $tpl->setVar('Account-Data', $editor->getHTML());
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseAccountPasswordEditor(Template $tpl)
    {
        $editor = new PasswordEditor(clone Application::getLoggedUser());
        $tpl->setVar('Account-Password', $editor->getHTML());
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseAccountAddressEditor(Template $tpl)
    {
        $editor = new AddressEditor(clone Application::getLoggedUser());
        $tpl->setVar('Account-Address', $editor->getHTML());
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseAccountSubscriptionEditor(Template $tpl)
    {
        $editor = new SubscriptionEditor(clone Application::getLoggedUser());
        $tpl->setVar('Account-Subscriptions', $editor->getHTML());
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseAccountOrders(Template $tpl)
    {
        $um = new UserManager();
        $orders = $um->getUserOrders(Application::getLoggedUser());
        if (empty($orders)) {
            $tpl->parseB2V('Orders', 'NO-ORDER');
            return;
        }

        foreach ($orders AS $order) {
            $tpl->setVar('OrderId', $order->id);
            $tpl->setVar('OrderDate', $order->createDate);
            $tpl->setVar('OrderState',
                $order->state == PurchaseManager::STATE_PAID
                    ? 'Оплачен'
                    : 'Не оплачен');
            $tpl->setVar('OrderTotal', $order->total);
            $tpl->setVar('OrderPayment',
                $order->paymentType == PurchaseManager::PAYMENT_ONLINE
                    ? 'Онлайн'
                    : 'Наличные');
            $tpl->parseB2V('Orders', 'ORDER', TRUE);
        }
    }

}
