<?php

namespace Navigation;

class View
{

    private static $_topMenu = array(
        '/special/' => array('Спецпредложение'),
        '/about' => array('Почему именно мы'),
        '/actions/' => array('Акции недели', 'actions'),
        '/discount' => array('Скидки'),
        '/delivery' => array('Доставка/оплата'),
        '/shops' => array('Где купить?')
    );

    /**
     *
     * @return string
     */
    public static function getHTML()
    {
        $navTpl = new \Template(CFG_PATH_TPL . 'main_nav.html');
        self::_parseTopMenu($navTpl);

        $cm = new \CategoryManager();
        $categories = $cm->getAll('parent_id=0 AND state=1');
        foreach ($categories AS $category) {
            $navTpl->setVar('ParentCaption', $category->caption);
            $navTpl->setVar('ParentUrl', $category->url);
            $navTpl->setVar('Children', '');
            $children = $cm->getAll('parent_id=:parent_id AND state=1',
                array('parent_id' => $category->id));
            foreach ($children AS $child) {
                $navTpl->setVar('Caption', $child->caption);
                $navTpl->setVar('Url', $child->url);
                $navTpl->parseB2V('Children', 'CHILD', TRUE);
            }
            $navTpl->parseB2V('Categories', 'MENU-GROUP', TRUE);
        }
        return $navTpl->fillTemplate();
    }

    /**
     *
     * @param \Template $navTpl
     * @return void
     */
    private static function _parseTopMenu(\Template $navTpl)
    {
        foreach (self::$_topMenu AS $link => $options) {
            $navTpl->setVar('TM-Class',
                empty($options[1])
                    ? ''
                    : $options[1]);
            $navTpl->setVar('TM-Link', $link);
            if ($link == $_SERVER['REQUEST_URI']) {
                $navTpl->setVar('TM-Class', ' active_menu', TRUE);
            }
            $navTpl->setVar('TM-Caption', $options[0]);
            $navTpl->parseB2V('TopMenuElements', 'TOP-MENU-ELEMENT', TRUE);
        }
    }

}
