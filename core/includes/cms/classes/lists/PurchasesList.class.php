<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class PurchasesList extends AbstractList
{

    protected $_canRemove = FALSE;
    protected $_canAdd = FALSE;
    protected $_multySelect = FALSE;
    protected $_sortable = FALSE;
    protected $_showFilter = TRUE;
    protected $_entityName = 'Purchase';
    private $_userManager;

    public function __construct()
    {
        parent::__construct();
        $this->_userManager = new UserManager();
    }

    protected function _getFields()
    {
        return array(
            'userId' => array(Field::CAPTION, 'ID пользователя', TRUE),
            'userEmail' => array(Field::CAPTION, 'Email пользователя'),
            'state' => array(
                Field::SELECT, 'Статус', self::_getSelectboxPaymentStateOptions(),
                TRUE
            ),
            'deliveryType' => array(
                Field::SELECT, 'Доставка', self::_getSelectboxDeliveryTypeOptions()
            ),
            'paymentType' => array(
                Field::SELECT, 'Способ оплаты', self::_getSelectboxPaymentTypeOptions()
            ),
            'totalPure' => array(Field::CAPTION, 'Стоимость товаров'),
            'discount' => array(Field::CAPTION, 'Скидка'),
            'total' => array(Field::CAPTION, 'Финальная стоимость', TRUE),
            'createDate' => array(Field::DATE, 'Дата создания', TRUE)
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

    /**
     *
     * @param Purchase $item
     * @return void
     */
    protected function _buildItem($item)
    {
        $user = $this->_userManager->getById($item->userId);
        $item->userEmail = $user->email;
        parent::_buildItem($item);
    }

}
