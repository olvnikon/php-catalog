<?php

/**
 * @author Никонов Владимир Андреевич
 */
class CatalogPage extends AbstractProductPage
{

    /**
     *
     * @var CategoryManager
     */
    private $_categoryManager;

    public function __construct()
    {
        parent::__construct();
        $this->_categoryManager = new CategoryManager();
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _prepareProductTpl()
    {
        if (!Request::isEmpty('special')) {
            $this->_parseItems($this->_getSpecialProducts());
            $this->_parsePageParamsByCaption('Специальные предложения');
        } elseif (!Request::isEmpty('actions')) {
            $this->_parseItems($this->_getActionsProducts());
            $this->_parsePageParamsByCaption('Акции недели');
        } else {
            $category = $this->_getCategory();
            $this->_parsePageParamsByCategory($category);
            $this->_parseItems($this->_getProducts($category));
        }
    }

    /**
     *
     * @param Product[] $products
     * @return void
     */
    private function _parseItems($products)
    {
        foreach ($products AS $product) {
            $this->_productTpl->setVar(
                'Products',
                ProductView::getShortHtml(
                    ProductBuilder::patchItem($product)
                ), TRUE
            );
        }
        $this->_productTpl->parseB2V('PageContent', 'CATEGORIES');
    }

    /**
     *
     * @return Product[]
     */
    private function _getSpecialProducts()
    {
        return $this->_productManager->getAll('is_special=1 AND state=1');
    }

    /**
     *
     * @return void
     */
    private function _parsePageParamsByCaption($caption)
    {
        $this->_tpl->setVar('Page-Title', $caption);
        $this->_tpl->setVar('Page-Keywords', ", $caption");
        $this->_tpl->setVar('Page-Description', ", $caption");
        $this->_tpl->setVar('Breadcrumbs',
            BreadcrumbsView::getSimpleHtml($caption));
    }

    /**
     *
     * @return Product[]
     */
    private function _getActionsProducts()
    {
        return $this->_productManager->getAll('NULLIF(new_price, "") IS NOT NULL AND state=1');
    }

    /**
     *
     * @return Category
     */
    private function _getCategory()
    {
        $category = $this->_categoryManager->getAll('url=:url',
            array('url' => Request::isEmpty('subcategory')
                ? Request::get('category')
                : Request::get('subcategory')), 0, 1);
        if (empty($category)) {
            Request::goToLocalPage('index');
        }

        return $category[0];
    }

    /**
     *
     * @param Product $item
     * @return void
     */
    private function _parsePageParamsByCategory(Category $item)
    {
        $this->_tpl->setVar('Page-Title', $this->_getPageTitle($item));
        $this->_tpl->setVar('Page-Keywords', ', ' . $item->metaKeywords);
        $this->_tpl->setVar('Page-Description', ', ' . $item->metaDescription);
        $this->_tpl->setVar('Breadcrumbs',
            BreadcrumbsView::getHtmlByCategory($item));
    }

    /**
     *
     * @param Category $item
     * @return string
     */
    private function _getPageTitle(Category $item)
    {
        if (empty($item->parentId)) {
            return $item->caption;
        }

        $parent = $this->_categoryManager->getById($item->parentId);
        return $item->caption . ' - ' . $parent->caption;
    }

    /**
     *
     * @param Category $category
     * @return Product[]
     */
    private function _getProducts(Category $category)
    {
        return $this->_productManager->getAll(
                sprintf(
                    'category IN (%s) AND state=1',
                    implode(',', $this->_getChildrenIds($category))
                )
        );
    }

    /**
     *
     * @param Category $category
     * @return int[]
     */
    private function _getChildrenIds(Category $category)
    {
        $children = $this->_categoryManager->getAll(
            'parent_id=:parent_id  AND state=1',
            array('parent_id' => $category->id)
        );
        $categoriesIds = array();
        if (!empty($children)) {
            foreach ($children AS $child) {
                $categoriesIds[] = $child->id;
            }
        }

        return empty($categoriesIds)
            ? array($category->id)
            : $categoriesIds;
    }

}
