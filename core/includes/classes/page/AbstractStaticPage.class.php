<?php

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractStaticPage extends AbstractSitePage
{

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $spm = new StaticPageManager();
        $page = $spm->getById($this->_getPageId());
        $this->_parsePageFromDB($page);
    }

    /**
     * @return int
     */
    abstract protected function _getPageId();

    /**
     * Заполнить переменные, используя StaticPage
     *
     * @param StaticPage $staticPage
     * @return void
     */
    protected function _parsePageFromDB(StaticPage $staticPage)
    {
        $this->_tpl->setVar('Page-Keywords', ', ' . $staticPage->keywords);
        $this->_tpl->setVar('Page-Title', $staticPage->name);
        $this->_tpl->setVar('Page-Content', $staticPage->content, TRUE);
        $this->_tpl->setVar('Page-Description', ', ' . $staticPage->description);
        $this->_tpl->setVar('Breadcrumbs',
            BreadcrumbsView::getSimpleHtml($staticPage->name));
    }

}
