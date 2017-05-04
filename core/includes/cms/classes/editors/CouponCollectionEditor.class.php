<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class CouponCollectionEditor extends AbstractEditor
{

    protected $_requiredFields = array(
        'name', 'state', 'startDate',
        'stopDate', 'discount', 'couponCount'
    );
    protected $_entityName = 'CouponCollection';

    /**
     *
     * @param CouponCollection $item
     * @return void
     */
    protected function _initItem($item)
    {
        parent::_initItem($item);
        if (!$this->_isNewItem) {
            $this->_item->discountTypeText = CouponCollectionManager::getDiscountTypeText(
                    $this->_item->discountType
            );
        }
    }

    protected function _getControls()
    {
        return $this->_isNewItem
            ? $this->_getNewCollectionControls()
            : $this->_getExistedCollectionControls();
    }

    /**
     *
     * @return array
     */
    private function _getNewCollectionControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Наименование'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            ),
            'startDate' => array(Field::DATE, 'Дата начала'),
            'stopDate' => array(Field::DATE, 'Дата конца'),
            'discount' => array(Field::TEXT, 'Размер скидки'),
            'discountType' => array(Field::SELECTBOX, 'Тип скидки',
                $this->_getDiscountTypeOptions()),
            'couponCount' => array(Field::TEXT, 'Количество купонов'),
        );
    }

    /**
     *
     * @return string
     */
    private function _getDiscountTypeOptions()
    {
        return array(
            CouponCollectionManager::DISCOUNT_TYPE_PERCENT => '%',
            CouponCollectionManager::DISCOUNT_TYPE_RUB => 'руб.'
        );
    }

    /**
     *
     * @return array
     */
    private function _getExistedCollectionControls()
    {
        return array(
            'name' => array(Field::CAPTION, 'Наименование'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            ),
            'startDate' => array(Field::DATE, 'Дата начала'),
            'stopDate' => array(Field::DATE, 'Дата конца'),
            'discount' => array(Field::CAPTION, 'Размер скидки'),
            'discountTypeText' => array(Field::CAPTION, 'Тип скидки',
                $this->_getDiscountTypeOptions()),
            'couponCount' => array(Field::CAPTION, 'Количество купонов'),
        );
    }

}
