<?php

class Application
{

    public $config;
    private static $_instance;
    public $security;
    public $settings;

    /**
     *
     * @param array $config
     * @return void
     */
    public function __construct($config = array())
    {
        $this->_processConfig($config);
        self::$_instance = $this;
    }

    /**
     *
     * @param array $config
     * @return void
     */
    private function _processConfig($config)
    {
        $this->config = $config;
        $this->security = new $config['SECURITY_MANAGER'];
        $this->settings = new $config['SETTING_MANAGER'];
    }

    /**
     * @return void
     */
    public function run()
    {
        $pageDB = $this->_getPage(
            Request::isEmpty('page')
                ? 'index'
                : Request::get('page')
        );
        if (empty($pageDB)) {
            Request::goLocation(CFG_SITE_DOMAIN);
        }

        $this->_showPage($pageDB);
    }

    /**
     *
     * @param string $pageName
     * @param boolean $isCms
     * @return Page
     */
    private function _getPage($pageName, $isCms = FALSE)
    {
        $pageManager = new PageManager();
        return $pageManager->getPageByName($pageName, $isCms);
    }

    /**
     *
     * @param Page $pageDB
     * @return void
     */
    private function _showPage(Page $pageDB)
    {
        $page = new $pageDB->class();
        $page->process();
        $page->show();
    }

    /**
     * @return void
     */
    public function runCms()
    {
        $reqPage = $GLOBALS['cmsPages']->getFirstChildren(
            Request::get('page'), Request::get('tab')
        );
        $this->_checkAutorization($reqPage);

        if (empty($reqPage)) {
            Request::goLocation('/cms/' . $GLOBALS['cmsPages']->getFirstNonServicePage());
        }
        $this->_checkPageExists($reqPage)
            ->_checkIsServicePage(Request::get('page'))
            ->_showPage(
                $this->_getPage($reqPage, TRUE)
        );
    }

    /**
     *
     * @param string $reqPage
     * @return Application
     */
    private function _checkAutorization($reqPage)
    {
        if ($this->security->isLogged()) {
            if (!$this->security->user->hasAccessToCms()) {
                Request::goToLocalPage('/');
            }
        } elseif ($reqPage != \Menu\API::AUTORIZATION) {
            Request::goToLocalPage('cms/' . \Menu\API::AUTORIZATION);
        }
    }

    /**
     *
     * @param string $reqPage
     * @return Application
     */
    private function _checkPageExists($reqPage)
    {
        if (!$GLOBALS['cmsPages']->hasPage($reqPage)) {
            Request::goToLocalPage('cms/' . $GLOBALS['cmsPages']->getFirstNonServicePage());
        }

        return $this;
    }

    /**
     *
     * @param string $reqPage
     * @return Application
     */
    private function _checkIsServicePage($reqPage)
    {
        if ($GLOBALS['cmsPages']->isServicePage($reqPage)) {
            return $this;
        }

        if (!$this->security->user->hasAccessToPage($reqPage)) {
            Request::goToLocalPage('cms/' . $GLOBALS['cmsPages']->getFirstNonServicePage());
        }

        return $this;
    }

    /**
     *
     * @return Application
     */
    public static function getInstance()
    {
        return self::$_instance;
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public static function getConfigOption($name)
    {
        return empty(self::$_instance->config[$name])
            ? ''
            : self::$_instance->config[$name];
    }

    /**
     *
     * @return User|NULL
     */
    public static function getLoggedUser()
    {
        return empty(self::$_instance->security)
            || !self::$_instance->security->isLogged()
            ? NULL
            : self::$_instance->security->user;
    }

}
