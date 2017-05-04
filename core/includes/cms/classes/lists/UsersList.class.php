<?php

use Lists\Dictionary\Field;

require_once 'dictionary/UserType.php';

/**
 * @author Никонов Владимир Андреевич
 */
class UsersList extends AbstractList
{

    protected $_multySelect = TRUE;
    protected $_entityName = 'User';
    protected $_showFilter = TRUE;

    public function __construct()
    {
        $this->_canRemove = $this->_canAdd = Application::getLoggedUser()->isAdmin();
        parent::__construct();
    }

    protected function _getFields()
    {
        return array(
            'id' => array(Field::CAPTION, '#', TRUE),
            'name' => array(Field::CAPTION, 'Имя', TRUE),
            'lastName' => array(Field::CAPTION, 'Фамилия', TRUE),
            'email' => array(Field::CAPTION, 'Email', TRUE),
            'type' => array(
                Field::SELECT, 'Статус системы',
                Dictionary\UserType::getAll(), TRUE
            ),
            'state' => array(
                Field::SELECT, 'Статус', self::_getSelectboxOnOffOptions(), TRUE
            ),
            'phone' => array(Field::CAPTION, 'Телефон', TRUE),
            'createDate' => array(Field::CAPTION, 'Зарегистрирован'),
            'logonDate' => array(Field::CAPTION, 'Последний вход')
        );
    }

    protected function _parseItemActions($item)
    {
        $this->_tpl->setVar('Action', '');
        if ($item->type != UTYPE_ADMIN) {
            parent::_parseItemActions($item);
        } elseif (Application::getLoggedUser()->isAdmin()) {
            $this->_canRemove = FALSE;
            parent::_parseItemActions($item);
            $this->_canRemove = TRUE;
        } else {
            $this->_tpl->parseB2V('Fields', 'ITEM-ACTIONS', TRUE);
        }
    }

    protected function _processRemove()
    {
        $toRemove = Request::get('idItemRemove');
        $itemsToRemove = is_array($toRemove)
            ? $toRemove
            : array($toRemove);
        foreach ($itemsToRemove AS $itemToRemove) {
            $item = $this->_manager->getById(intval($itemToRemove));
            if ($item->type != UTYPE_ADMIN) {
                $this->_manager->delete(intval($itemToRemove));
            }
        }
        exit('1');
    }

}
