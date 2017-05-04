<?php

use Editor\Dictionary\Field;

require_once 'dictionary/UserType.php';

/**
 * @author Никонов Владимир Андреевич
 */
class UserEditor extends AbstractEditor
{

    protected $_requiredFields = array(
        'email', 'nePasswd', 'type', 'phone', 'name', 'lastName', 'state'
    );
    protected $_entityName = 'User';

    protected function _getControls()
    {
        $controls = array(
            'email' => array($this->_isNewItem
                    ? Field::TEXT
                    : Field::CAPTION, 'Логин / Email'),
            'nePasswd' => array(Field::TEXT, 'Пароль'),
            'type' => array(
                Field::SELECTBOX, 'Тип пользователя',
                Dictionary\UserType::getAll()
            ),
            'phone' => array(Field::TEXT, 'Телефон'),
            'name' => array(Field::TEXT, 'Имя'),
            'lastName' => array(Field::TEXT, 'Фамилия'),
            'town' => array(Field::TEXT, 'Город'),
            'address' => array(Field::TEXT, 'Адрес'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            ),
            'subscription' => array(
                Field::SELECTBOX, 'Подписка', self::_getSelectboxOnOffOptions()
            ),
            'logonDate' => array(Field::CAPTION, 'Дата последней авторизации'),
            'company' => array(Field::TEXT, 'Компания'),
            'companyPhone' => array(Field::TEXT, 'Телефон компании'),
            'fax' => array(Field::TEXT, 'Факс'),
            'postcode' => array(Field::TEXT, 'Индекс')
        );

        if ($this->_hasPrivileges()) {
            $this->_addPrivileges($controls);
        }

        return $controls;
    }

    protected function _processSpecialFields()
    {
        $this->_item->password = md5($this->_item->nePasswd);
    }

    /**
     * Получить опции привелегий
     *
     * @return array
     */
    private static function _getPriveleges()
    {
        $priveleges = array();
        foreach ($GLOBALS['cmsPages']->getPages() AS $page) {
            if ($page->isService) {
                continue;
            }

            $priveleges[$page->page] = $page->caption;
        }

        return $priveleges;
    }

    /**
     * Администратор ли пользователь?
     *
     * @return boolean
     */
    private function _hasPrivileges()
    {
        return $this->_isNewItem
            || $this->_item->type == UTYPE_MODERATOR;
    }

    /**
     * Добавить контролы для не администраторов
     *
     * @param array $controls
     * @return void
     */
    private function _addPrivileges(&$controls)
    {
        $controls['priveleges'] = array(
            Field::CHECKBOXGROUP, 'Права', self::_getPriveleges()
        );
    }

}
