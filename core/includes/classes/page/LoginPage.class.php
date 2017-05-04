<?php

/**
 * @author Никонов Владимир Андреевич
 */
class LoginPage extends AbstractSitePage
{

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $tpl = new Template(CFG_PATH_TPL . 'login.html');
        $this->_parseStaticPages($tpl);
        $this->_tpl->setVar('Page-Content', $tpl->fillTemplate());
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseStaticPages(Template $tpl)
    {
        $spm = new StaticPageManager();

        $header = $spm->getById(PAGE_TYPE_AUTH_HEADER);
        $tpl->setVar('Login-Header', $header->content);

        $newUser = $spm->getById(PAGE_TYPE_LOGIN_NEW);
        $tpl->setVar('Login-NewUsers', $newUser->content);

        $oldUser = $spm->getById(PAGE_TYPE_LOGIN_OLD);
        $tpl->setVar('Login-OldUsers', $oldUser->content);
    }

    public function process()
    {
        if (Application::getLoggedUser()) {
            Request::goToLocalPage('/');
        }

        if (!Request::isEmpty('login') && !Request::isEmpty('password')) {
            if (Application::getInstance()->security->autorize(Request::get('login'),
                    Request::get('password'), TRUE)) {
                Request::goToLocalPage('/');
            }

            $this->_tpl->parseB2V('ErrorLogon', 'ERROR-LOGON');
        }
    }

}
