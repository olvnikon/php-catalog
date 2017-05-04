<?php

class BreadcrumbsView
{

    /**
     *
     * @param Category $item
     * @return string
     */
    public static function getHtmlByCategory(Category $item)
    {
        $tpl = new Template(CFG_PATH_TPL . 'breadcrumbs.html');
        self::_parseRoot($tpl);
        self::_parseMiddleCategory($tpl, $item);
        self::_parseLastCategory($tpl, $item->caption);
        return $tpl->parseB('BREADCRUMBS');
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private static function _parseRoot(Template $tpl)
    {
        $tpl->setVar('Caption', 'Главная');
        $tpl->setVar('Url', '/');
        $tpl->parseB2V('Breadcrumbs', 'LINK-BREADCRUMB', TRUE);
    }

    /**
     *
     * @param Template $tpl
     * @param Category $item
     * @return void
     */
    private static function _parseMiddleCategory(Template $tpl, Category $item)
    {
        if (empty($item->parentId)) {
            return;
        }

        $cm = new CategoryManager();
        self::_parseByCategory($tpl, $cm->getById($item->parentId));
    }

    /**
     *
     * @param Template $tpl
     * @param Category $item
     * @return void
     */
    private static function _parseByCategory(Template $tpl, Category $item)
    {
        $tpl->setVar('Caption', $item->caption);
        $tpl->setVar('Url', '/catalog/' . $item->url . '/');
        $tpl->parseB2V('Breadcrumbs', 'LINK-BREADCRUMB', TRUE);
    }

    /**
     *
     * @param Template $tpl
     * @param string $caption
     * @return void
     */
    private static function _parseLastCategory(Template $tpl, $caption)
    {
        $tpl->setVar('Caption', $caption);
        $tpl->parseB2V('Breadcrumbs', 'LAST-BREADCRUMB', TRUE);
    }

    /**
     *
     * @param Product $item
     * @return string
     */
    public static function getHtmlByProduct(Product $item)
    {
        $tpl = new Template(CFG_PATH_TPL . 'breadcrumbs.html');
        self::_parseRoot($tpl);
        self::_parseByCategory($tpl, self::_getProductCategory($item));
        self::_parseLastCategory($tpl, $item->caption);
        return $tpl->parseB('BREADCRUMBS');
    }

    /**
     *
     * @param Product $item
     * @return Category
     */
    private static function _getProductCategory(Product $item)
    {
        $cm = new CategoryManager();
        return $cm->getById($item->category);
    }

    /**
     *
     * @return string
     */
    public static function getSimpleHtml($caption)
    {
        $tpl = new Template(CFG_PATH_TPL . 'breadcrumbs.html');
        self::_parseRoot($tpl);
        self::_parseLastCategory($tpl, $caption);
        return $tpl->parseB('BREADCRUMBS');
    }

}
