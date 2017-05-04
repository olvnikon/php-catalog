<?php

class ProductBuilder
{

    /**
     *
     * @var ProductManager
     */
    private static $_manager;

    public static function getItem($id)
    {
        self::_initManager();
        $product = self::$_manager->getById($id);
        if (empty($product)) {
            return FALSE;
        }

        return self::patchItem($product);
    }

    /**
     * @return void
     */
    private static function _initManager()
    {
        if (empty(self::$_manager)) {
            self::$_manager = new ProductManager();
        }
    }

    /**
     * @return void
     */
    private static function _patchImages(Product $product)
    {
        $ppm = new ProductPhotoManager();
        $product->images = $ppm->getAll(
            'product_id=:product_id', array('product_id' => $product->id)
        );
        $product->firstImage = empty($product->images[0])
            ? ''
            : $product->images[0]->fileName;
    }

    /**
     *
     * @param Product $product
     * @return Product
     */
    public static function patchItem(Product $product)
    {
        self::_patchImages($product);
        return $product;
    }

}
