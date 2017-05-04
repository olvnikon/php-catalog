<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class ProductEditor extends AbstractEditor
{

    protected $_requiredFields = array('caption', 'state', 'category', 'price');
    protected $_entityName = 'Product';
    private $_photoManager;

    /**
     *
     * @param Product $item
     * @return void
     */
    protected function _initItem($item)
    {
        parent::_initItem($item);

        $this->_photoManager = new ProductPhotoManager();
        $this->_item->images = array();
        if ($this->_isNewItem) {
            return;
        }

        $images = $this->_photoManager->getAll(
            'product_id=:product_id', array('product_id' => $this->_item->id)
        );
        foreach ($images AS $image) {
            $this->_item->images[] = $image->fileName;
        }
    }

    protected function _getControls()
    {
        return array(
            'caption' => array(Field::TEXT, 'Наименование'),
            'url' => array(Field::URL, 'Ссылка'),
            'images' => array(Field::IMAGEGROUP, 'Фотографии'),
            'description' => array(Field::TEXTEDITOR, 'Описание'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            ),
            'category' => array(
                Field::SELECTBOX, 'Категория', $this->_getCategoryOptions()
            ),
            'isSpecial' => array(
                Field::SELECTBOX, 'Специальное предложение', self::_getSelectboxOnOffOptions()
            ),
            'nominalWeight' => array(Field::TEXT, 'Номинальный вес'),
            'nominalCount' => array(Field::TEXT, 'Количество в упаковке'),
            'price' => array(Field::PRICE, 'Цена'),
            'newPrice' => array(Field::PRICE, 'Цена по акции'),
            'priceFor' => array(
                Field::SELECTBOX, 'Цена за', self::_getPriceForOptions()
            ),
            'containInfo' => array(Field::TEXTAREA, 'Состав'),
            'metaKeywords' => array(Field::TEXTAREA, 'Meta: keywords'),
            'metaDescription' => array(Field::TEXTAREA, 'Meta: description')
        );
    }

    /**
     *
     * @return array
     */
    private function _getCategoryOptions()
    {
        $cm = new CategoryManager();
        $categories = $cm->getAll('parent_id!=0');
        $options = array();
        foreach ($categories AS $category) {
            $options[$category->id] = $category->caption;
        }
        return $options;
    }

    /**
     *
     * @return array
     */
    private function _getPriceForOptions()
    {
        return array(
            0 => '',
            ProductManager::PRICE_FOR_KG => 'кг',
            ProductManager::PRICE_FOR_AMOUNT => 'шт'
        );
    }

    protected function _saveToDB()
    {
        parent::_saveToDB();
        $existed = $this->_photoManager->getAll(
            'product_id=:product_id', array('product_id' => $this->_item->id)
        );

        $sortOrder = 10;
        foreach ($this->_item->images as $image) {
            $existedPhoto = $this->_extractExistedPhoto($image, $existed);
            if (empty($existedPhoto)) {
                $newImage = new ProductPhoto();
                $newImage->fileName = $image;
                $newImage->sortOrder = $sortOrder;
                $newImage->productId = $this->_item->id;
                $this->_photoManager->create($newImage);
            } else {
                $existedPhoto->sortOrder = $sortOrder;
                $this->_photoManager->update($existedPhoto);
            }
            $sortOrder += 10;
        }

        foreach ($existed AS $photo) {
            $this->_photoManager->deletePhoto($photo);
        }
    }

    /**
     *
     * @param string $filename
     * @param ProductPhoto[] $existed
     * @return ProductPhoto|FALSE
     */
    private function _extractExistedPhoto($filename, &$existed)
    {
        foreach ($existed AS $key => $photo) {
            if ($filename == $photo->fileName) {
                unset($existed[$key]);
                return $photo;
            }
        }

        return FALSE;
    }

}
