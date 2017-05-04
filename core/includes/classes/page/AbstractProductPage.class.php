<?php

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractProductPage extends AbstractSitePage
{

    /**
     *
     * @var ProductManager
     */
    protected $_productManager;

    /**
     *
     * @var Template
     */
    protected $_productTpl;

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_productManager = new ProductManager();
        $this->_productTpl = new Template(CFG_PATH_TPL . 'products.html');
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $this->_prepareProductTpl();
        $this->_parseBanner();
        $this->_tpl->setVar('Page-Content', $this->_productTpl->fillTemplate());
    }

    /**
     * @return void
     */
    abstract protected function _prepareProductTpl();

    /**
     * @return void
     */
    protected function _parseBanner()
    {
        $bm = new BannerManager();
        $banners = $bm->getAll('state=1');
        $this->_productTpl->setVar('Banner',
            $banners[rand(0, count($banners) - 1)]->content);
    }

}
