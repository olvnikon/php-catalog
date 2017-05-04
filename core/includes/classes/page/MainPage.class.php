<?php

/**
 * @author Никонов Владимир Андреевич
 */
class MainPage extends AbstractSitePage
{

    public function process()
    {
        if (!Request::isAjaxRequest()) {
            return;
        }

        if (Request::issetParam('subscription_email')) {
            $this->_processSubscription();
        }
    }

    /**
     * @return void
     */
    private function _processSubscription()
    {
        $email = Request::get('subscription_email');
        if (empty($email)) {
            exit('0');
        }

        $sem = new SubscriptionEmailManager();
        if ($sem->getCounts('email=:email', array('email' => $email))) {
            exit('2');
        }

        $subscription = new SubscriptionEmail();
        $subscription->email = $email;
        $sem->create($subscription);
        exit('1');
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $tpl = new Template(CFG_PATH_TPL . 'main_page.html');
        $tpl->setVar('Slider', $this->_getSliderHtml());
        $this->_parseBanners($tpl);
        $this->_parsePageSettings($tpl);
        $this->_tpl->setVar('Page-Content', $tpl->fillTemplate());
    }

    /**
     *
     * @return string
     */
    private function _getSliderHtml()
    {
        $sm = new SlideManager();
        return SlideView::getHTML(
                new Template(CFG_PATH_TPL . 'main_slider.html'), $sm->getAll()
        );
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseBanners(Template $tpl)
    {
        $cm = new CategoryManager();
        $categories = $cm->getAll('state=1 AND featured=1', array(), 0, 3);
        foreach ($categories AS $category) {
            if (empty($category->parentId)) {
                $tpl->setVar('Banner-Link', $category->url);
            } else {
                $parent = $cm->getById($category->parentId);
                $tpl->setVar('Banner-Link', $parent->url . '/' . $category->url);
            }
            $tpl->setVar('Banner-Caption', $category->caption);
            $tpl->setVar('Banner-Image', $category->image);
            $tpl->parseB2V('Banners', 'BANNER', TRUE);
        }
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parsePageSettings(Template $tpl)
    {
        $tpl->setVar('TopBaner-Link',
            $GLOBALS['SettingManager']->getParamValue('Ссылка правого баннера главной страницы'));
        $tpl->setVar('TopBaner-Image',
            $GLOBALS['SettingManager']->getParamValue('Правый баннер главной страницы'));
    }

}
