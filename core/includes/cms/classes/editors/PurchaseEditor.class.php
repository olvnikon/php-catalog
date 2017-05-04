<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class PurchaseEditor extends AbstractEditor
{

    protected $_requiredFields = array('name', 'state');
    protected $_entityName = 'Purchase';

    /**
     *
     * @param Purchase $item
     * @return void
     */
    protected function _initItem($item)
    {
        parent::_initItem($item);

        $this->_item->productsLinks = '';
        $pm = new ProductManager();
        foreach ($this->_item->products AS $product) {
            $dbProduct = $pm->getById($product->id);
            $dbProduct->caption .= ' x ' . $product->count;
            $this->_item->productsLinks .= ProductView::getShortHtml(
                    ProductBuilder::patchItem($dbProduct), TRUE
            );
        }
    }

    protected function _getControls()
    {
        return array(
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxPaymentStateOptions()
            ),
            'deliveryType' => array(
                Field::SELECTBOX, 'Доставка', self::_getSelectboxDeliveryTypeOptions()
            ),
            'paymentType' => array(
                Field::SELECTBOX, 'Способ оплаты', self::_getSelectboxPaymentTypeOptions()
            ),
            'comment' => array(Field::CAPTION, 'Комментарий'),
            'contactPhone' => array(Field::CAPTION, 'Контактный телефон'),
            'couponId' => array(Field::CAPTION, 'ID купона'),
            'totalPure' => array(Field::CAPTION, 'Стоимость товаров'),
            'discount' => array(Field::CAPTION, 'Скидка'),
            'total' => array(Field::CAPTION, 'Финальная стоимость'),
            'productsLinks' => array(Field::CAPTION, 'Товары')
        );
    }

    /**
     *
     * @return array
     */
    private static function _getSelectboxPaymentStateOptions()
    {
        return array(
            PurchaseManager::STATE_UNPAID => 'Не оплачено',
            PurchaseManager::STATE_PAID => 'Оплачено'
        );
    }

    /**
     *
     * @return array
     */
    private static function _getSelectboxDeliveryTypeOptions()
    {
        return array(
            PurchaseManager::TYPE_COURIER => 'Курьер',
            PurchaseManager::TYPE_PICKUP => 'Самовывоз'
        );
    }

    /**
     *
     * @return array
     */
    private static function _getSelectboxPaymentTypeOptions()
    {
        return array(
            PurchaseManager::PAYMENT_CASH => 'Наличные',
            PurchaseManager::PAYMENT_ONLINE => 'Online'
        );
    }

}
