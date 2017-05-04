<?php

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractPage
{

    /**
     *
     * @var Template
     */
    protected $_tpl;

    /**
     *
     * @var string
     */
    protected $_tplDir = '';

    /**
     *
     * @var string
     */
    protected $_tplFile = '';

    /**
     * Конструктор класса
     *
     * @return void
     */
    public function __construct()
    {
        $this->_initTpl();
    }

    /**
     * Инициализация шаблона
     *
     * @return void
     */
    protected function _initTpl()
    {
        $this->_tpl = new Template($this->_tplDir . $this->_tplFile);
    }

    /**
     * Отобразить содержимое страницы
     *
     * @return void
     */
    public function show()
    {
        $this->_buildContent();
        header('Content-Type: text/html; charset=utf-8');
        echo $this->_tpl->fillTemplate();
    }

    /**
     * Построить содержимое страницы
     *
     * @return void
     */
    abstract protected function _buildContent();

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $this->_tpl->setVar('Page-Content', '');
    }

    /**
     * Процесс на странице (например, отправка формы)
     *
     * @return void
     */
    public function process()
    {

    }

}
