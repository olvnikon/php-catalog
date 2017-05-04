<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class FeedbackEditor extends AbstractEditor
{

    protected $_entityName = 'Feedback';

    protected function _initItem($item)
    {
        parent::_initItem($item);
        $pm = new ProductManager();
        $product = $pm->getById($this->_item->productId);
        $this->_item->productName = $product->caption;
    }

    protected function _getControls()
    {
        return array(
            'fio' => array(Field::CAPTION, 'Имя'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            ),
            'productName' => array(Field::CAPTION, 'Продукт'),
            'content' => array(Field::CAPTION, 'Описание отзыва'),
        );
    }

}
