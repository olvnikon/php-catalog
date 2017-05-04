<?php

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractCmsPage extends AbstractPage
{

    /**
     *
     * @var string[] Табы
     */
    private $_tabs = array();

    /**
     *
     * @var string
     */
    private $_activePage;

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = '';

    /**
     *
     * @var string Активный таб
     */
    private $_activeTab;

    /**
     *
     * @var string
     */
    protected $_tplDir = CFG_PATH_TEMPLATE_CMS;

    /**
     *
     * @var string
     */
    protected $_tplFile = 'general.html';

    /**
     * Конструктор класса
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $GLOBALS['cmsPages']->setActivePage(Request::get('page'));
        $this->_activePage = $GLOBALS['cmsPages']->getActivePage();
        $this->_breadTrails = $this->_activePage->caption . ' &Gt; ' . $this->_breadTrails;
        $this->_tabs = $this->_activePage->children;
        $this->_setActiveTab();
    }

    /**
     * Установить активную вкладку
     *
     * @return void
     */
    private function _setActiveTab()
    {
        $this->_activeTab = Request::isEmpty('tab')
            ? (empty($this->_tabs[0]->page)
                ? ''
                : $this->_tabs[0]->page)
            : Request::get('tab');
    }

    /**
     * Построить содержимое страницы
     *
     * @return void
     */
    protected function _buildContent()
    {
        $this->_parseCommonVars();
        $this->_parseCmsPages();
        $this->_parseTabs();
        $this->_parsePageContent();
    }

    /**
     * Распарсить общие переменные
     *
     * @return void
     */
    private function _parseCommonVars()
    {
        $this->_tpl->setVar('User-Name', Application::getLoggedUser()->email);
        $this->_tpl->setVar('CmsBreadtrail', $this->_breadTrails);
    }

    /**
     * Построить навигацию
     *
     * @return void
     */
    private function _parseCmsPages()
    {
        $this->_tpl->setVar('CmsMenu',
            $GLOBALS['cmsPages']->getHTML(
                new Template(CFG_PATH_TPL_CMS . 'cms_menu.html')
            )
        );
        $this->_tpl->setVar('Page-Name', $this->_activePage->page);
    }

    /**
     * Построить табы
     *
     * @return void
     */
    private function _parseTabs()
    {
        $this->_tpl->setVar('PageTabs', '');
        foreach ($this->_tabs AS $tab) {
            $this->_tpl->setVar('Tab-Caption', $tab->caption);
            $this->_tpl->parseB2V('Tab-Class',
                $tab->page == $this->_activeTab
                    ? 'PAGE-TAB-ACTIVE'
                    : 'PAGE-TAB-INACTIVE');
            $this->_tpl->setVar('Tab-Id', $tab->page);
            $this->_tpl->parseB2V('PageTabs', 'PAGE-TAB', TRUE);
        }
    }

}
