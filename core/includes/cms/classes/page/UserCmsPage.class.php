<?php

/**
 * @author Никонов Владимир Андреевич
 */
class UserCmsPage extends AbstractSimpleCmsPage
{

    /**
     *
     * @var string Хлебные крошки
     */
    protected $_breadTrails = 'Пользователи';

    /**
     *
     * @return \UserCmsPage
     */
    protected function _initManager()
    {
        $this->_manager = new UserManager();
        return parent::_initManager();
    }

    /**
     * Инициализация листа
     *
     * @return \UserCmsPage
     */
    protected function _initList()
    {
        $this->_list = new UsersList();
        return parent::_initList();
    }

    /**
     * Открыть редактор
     *
     * @return void
     */
    protected function _openEditor()
    {
        $item = $this->_manager->getById(intval(Request::get('idItem')));
        if (!empty($item)
            && $item->type == UTYPE_ADMIN
            && !Application::getLoggedUser()->isAdmin()) {
            exit();
        }

        parent::_openEditor();
    }

    /**
     * Инициализация редактора
     *
     * @return void
     */
    protected function _getEditor($item)
    {
        return new UserEditor($item);
    }

}
