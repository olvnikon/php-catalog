<?php

/**
 * @author Никонов Владимир Андреевич
 */
class AbstractEntity
{

    /**
     *
     * @var Object Entity manager
     */
    protected $_manager;

    /**
     * Конструктор класса
     * @param int $id
     * @return void
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->_initManager();
    }

    /**
     * Инициализация менеджера объекта
     *
     * @return void
     */
    private function _initManager()
    {
        $mngClass = get_class($this) . 'Manager';
        $this->_manager = new $mngClass();
    }

    public function __sleep()
    {
        $vars = get_class_vars('_' . get_class($this));
        return array_keys($vars['mapping']);
    }

    public function __wakeup()
    {
        $this->_initManager();
    }

}
