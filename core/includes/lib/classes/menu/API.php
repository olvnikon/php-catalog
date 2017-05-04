<?php

namespace Menu;

/**
 * @author Никонов Владимир Андреевич
 */
class API
{

    private $_elements = array();
    private $_activePage = '';

    const AUTORIZATION = 'autorization';
    const LOGOUT = 'exit';
    const FILE_UPLOAD = 'file_upload';

    /**
     *
     * @param string $activePage
     * @return void
     */
    public function setActivePage($activePage)
    {
        $this->_activePage = $activePage;
    }

    /**
     *
     * @return Element|FALSE
     */
    public function getActivePage()
    {
        return $this->getPageByName($this->_activePage);
    }

    /**
     *
     * @param string $page
     * @param string $tab
     * @return string
     * @todo There is a bug here!
     */
    public function getFirstChildren($page, $tab = '')
    {
        if (!empty($tab) && $this->hasPage($tab)) {
            return $tab;
        } elseif ($this->hasPage($page)) {
            $sPage = $this->getPageByName($page);
            return empty($sPage->children[0]->page)
                ? $sPage->page
                : $sPage->children[0]->page;
        } else {
            return FALSE;
        }
    }

    /**
     *
     * @param string $pageName
     * @return Element|FALSE
     */
    public function getPageByName($pageName)
    {
        foreach ($this->_elements AS $element) {
            if ($element->page == $pageName) {
                return $element;
            }
        }
        return FALSE;
    }

    /**
     *
     * @param \Menu\Element $element
     * @return void
     */
    public function addElement(Element $element)
    {
        $this->_elements[] = $element;
    }

    /**
     *
     * @return \Menu\Element[]
     */
    public function getPages()
    {
        return $this->_elements;
    }

    /**
     *
     * @param string $page
     * @return boolean
     */
    public function hasPage($page, $elements = array())
    {
        $elements = empty($elements)
            ? $this->_elements
            : $elements;
        foreach ($elements AS $element) {
            if ((!empty($element->children)
                && $this->hasPage($page, $element->children))
                || $page == $element->page) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     *
     * @return string|FALSE
     */
    public function getFirstNonServicePage()
    {
        foreach ($this->_elements AS $element) {
            if ($element->isService == FALSE
                && \Application::getLoggedUser()
                && \Application::getLoggedUser()->hasAccessToPage($element->page)) {
                return $element->page;
            }
        }

        return FALSE;
    }

    /**
     *
     * @param string $page
     * @return boolean
     */
    public function isServicePage($page)
    {
        foreach ($this->_elements AS $element) {
            if ($element->page == $page) {
                return $element->isService;
            }
        }

        return FALSE;
    }

    /**
     *
     * @param \Template $tpl
     * @return string
     */
    public function getHTML(\Template $tpl)
    {
        $tpl->setVar('CmsMenu', '');
        foreach ($this->_elements AS $element) {
            if ($element->isService
                || !\Application::getLoggedUser()
                || !\Application::getLoggedUser()->hasAccessToPage($element->page)) {
                continue;
            }

            $tpl->setVar('Menu-Page', $element->page);
            $tpl->setVar('Menu-Caption', $element->caption);
            $tpl->parseB2V('Menu-Class',
                $element->page == $this->_activePage
                    ? 'CMS-MENU-ACTIVE'
                    : 'CMS-MENU-INACTIVE');
            $tpl->parseB2V('CmsMenu', 'CMS-MENU-ELEMENT', TRUE);
        }

        return $tpl->fillTemplate();
    }

}
