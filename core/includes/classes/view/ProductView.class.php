<?php

class ProductView
{

    /**
     *
     * @param Product $product
     * @return string
     */
    public static function getShortHtml(Product $product, $isCms = FALSE)
    {
        $tpl = new Template(($isCms
                ? CFG_PATH_TPL_CMS
                : CFG_PATH_TPL) . 'view/product_short.html');
        $tpl->setVar('Product-Id', $product->id);
        $tpl->setVar('Product-Caption', $product->caption);
        $tpl->setVar('Product-Url', $product->url);
        self::_parsePrice($tpl, $product);
        self::_parsePhotos($tpl, $product);
        return $tpl->fillTemplate();
    }

    /**
     *
     * @param Template $tpl
     * @param Product $product
     * @return void
     */
    private static function _parsePrice(Template $tpl, Product $product)
    {
        $tpl->setVar('Product-Price', $product->price);
        $tpl->setVar('Product-NewPrice', $product->newPrice);

        if (!empty($product->priceFor)) {
            $tpl->setVar(
                'Product-PriceFor', '/ '
                . ProductManager::getPriceForCaption($product)
            );
        }

        if (empty($product->newPrice)) {
            $tpl->parseB2V('PriceLayout', 'SIMPLE-PRICE');
        } else {
            $tpl->parseB2V('PriceLayout', 'ACTION-PRICE');
        }
    }

    /**
     *
     * @param Template $tpl
     * @param Product $product
     * @return void
     */
    private static function _parsePhotos(Template $tpl, Product $product)
    {
        if (empty($product->firstImage)) {
            return;
        }

        $tpl->setVar('Product-MainImage', $product->firstImage);
        $tpl->parseB2V('MainImage', 'MAIN-IMAGE');

        foreach ($product->images AS $image) {
            $tpl->setVar('Product-Image', $image->fileName);
            $tpl->parseB2V('Miniature', 'MINIATURE', TRUE);
        }
        $tpl->parseB2V('Miniatures', 'MINIATURES');
    }

    /**
     *
     * @param Product $product
     * @return string
     */
    public static function getFullHtml(Product $product)
    {
        $tpl = new Template(CFG_PATH_TPL . 'view/product_full.html');
        self::processFullTemplate($tpl, $product);
        return $tpl->fillTemplate();
    }

    /**
     *
     * @param Template $tpl
     * @param Product $product
     * @return void
     */
    public static function processFullTemplate(Template $tpl, Product $product)
    {
        $tpl->setVar('Product-Id', $product->id);
        $tpl->setVar('Product-Caption', $product->caption);
        $tpl->setVar('Product-Description', $product->description);
        $tpl->setVar('Product-Weight', $product->nominalWeight);
        $tpl->setVar('Product-Contain', $product->containInfo);

        self::_parseProductCount($tpl, $product);
        self::_parsePrice($tpl, $product);
        self::_parsePhotos($tpl, $product);
    }

    /**
     * @param Template $tpl
     * @param Product $product
     * @return void
     */
    private static function _parseProductCount(Template $tpl, Product $product) {
        if (!empty($product->nominalCount)) {
            $tpl->setVar('Product-Count', $product->nominalCount);
            $tpl->parseB2V('ProductCount', 'PRODUCT-COUNT');
        }
    }

}
