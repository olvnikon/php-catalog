<?php

/**
 * @author Никонов Владимир Андреевич
 */
class AutorizationCmsPage extends AbstractPage
{

    /**
     *
     * @var string
     */
    protected $_tplDir = CFG_PATH_TEMPLATE_CMS;

    /**
     *
     * @var string
     */
    protected $_tplFile = 'autorization.html';

    protected function _buildContent()
    {

    }

    public function process()
    {
        if (Application::getLoggedUser()) {
            Request::goToLocalPage('cms/' . $GLOBALS['cmsPages']->getFirstNonServicePage());
        }

        if (!Request::isEmpty('login') && !Request::isEmpty('password')) {
            if (Application::getInstance()->security->autorize(Request::get('login'),
                    Request::get('password'), !Request::isEmpty('remember'))) {
                Request::goToLocalPage('cms/' . $GLOBALS['cmsPages']->getFirstNonServicePage());
            }

            $this->_tpl->parseB2V('ErrorLogon', 'ERROR-LOGON');
        }
    }

}
