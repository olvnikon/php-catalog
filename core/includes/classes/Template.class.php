<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Template
{

    /**
     *
     * @var string Содержимое файла шаблона
     */
    private $_tpl = '';

    /**
     *
     * @var array Переменные шаблона для заполнения
     */
    private $_vars = array();

    /**
     * Конструктор класса
     *
     * @param string $fileName Имя файла
     * @return void
     */
    public function __construct($fileName)
    {
        $this->_tpl = file_get_contents($fileName);
        $this->_fillCommonVars();
    }

    private function _fillCommonVars()
    {
        require_once CFG_PATH_DB_CLASS . 'Setting.class.php';
        $this->_vars = array(
            'STATIC_FILE_VERSION' => Application::getConfigOption('STATIC_FILE_VERSION'),
            'Site-Official' => CFG_SITE_DOMAIN,
            'General-Logo' => $GLOBALS['SettingManager']->getParamValue('Логотип (шапка) в формате png'),
            'REQUEST_URI' => $_SERVER['REQUEST_URI']
        );
    }

    /**
     * Установка значения переменной
     *
     * @param string $name Имя переменной в шаблоне
     * @param string $val Значение переменной
     * @param boolean $append Если FALSE - устанавливает новое значение; если TRUE - добавляет к существующему значению
     * @return void
     */
    public function setVar($name, $val, $append = FALSE)
    {
        $this->_vars[$name] = ($append
                ? $this->getVar($name)
                : '') . $val;
    }

    /**
     * Получить значение переменной
     *
     * @param string $name Имя переменной в шаблоне
     * @return string Значение переменной
     */
    public function getVar($name)
    {
        return key_exists($name, $this->_vars)
            ? $this->_vars[$name]
            : '';
    }

    /**
     * Распарсить блок и получить его содержимое
     *
     * @param string $blockName Имя блока
     * @return string Содержимое блока
     */
    public function parseB($blockName)
    {
        $content = array();
        preg_match('/\<\!\-\-\sBEGIN\s' . $blockName . '\s\-\-\>(.*)\<\!\-\-\sEND\s' . $blockName . '\s\-\-\>/s',
            $this->_tpl, $content);
        $preparedContent = empty($content)
            ? ''
            : trim($content[1]);
        return $this->_prepareContent($preparedContent);
    }

    /**
     * Распарсить блок в переменную
     *
     * @param string $var Имя переменной в шаблоне
     * @param string $blockName Имя блока
     * @param boolean $append Если FALSE - устанавливает новое значение; если TRUE - добавляет к существующему значению
     * @return void
     */
    public function parseB2V($var, $blockName, $append = FALSE)
    {
        $content = $this->parseB($blockName);
        $blockContent = ($append
                ? $this->getVar($var)
                : '') . $content;
        $this->setVar($var, $blockContent);
    }

    /**
     * Залить шаблон и получить его содержимое
     *
     * @return string Содержимое шаблона
     */
    public function fillTemplate()
    {
        return $this->_prepareContent($this->_tpl);
    }

    /**
     * Залить содержимое и убрать лишние блоки и переменные
     *
     * @param string $content Содержимое шаблона / блока
     * @return string
     */
    private function _prepareContent(&$content)
    {
        $this->_fillVars($content);
        self::_removeBlocks($content);
        self::_removeEmptyVars($content);

        return $content;
    }

    /**
     * Заполнить переменные шаблона
     *
     * @param string $content Содержимое шаблона / блока
     * @return void
     */
    private function _fillVars(&$content)
    {
        foreach ($this->_vars AS $name => $val) {
            $content = str_replace('{' . $name . '}', $val, $content);
        }
    }

    /**
     * Удалить ненужные блоки
     *
     * @param string $content Содержимое шаблона / блока
     * @return void
     */
    private static function _removeBlocks(&$content)
    {
        $blockNames = array();
        preg_match_all('/\<\!\-\-\sBEGIN\s([\w\s\-]+)\s\-\-\>/', $content,
            $blockNames);
        foreach ($blockNames[1] AS $blockName) {
            $content = preg_replace('/\<\!\-\-\sBEGIN\s' . $blockName . '\s\-\-\>.*\<\!\-\-\sEND\s' . $blockName . '\s\-\-\>/s',
                '', $content);
        }
    }

    /**
     * Удалить пустые переменные
     *
     * @param string $content Содержимое шаблона / блока
     * @return void
     */
    private static function _removeEmptyVars(&$content)
    {
        $content = preg_replace('/\{[\w\s\-]+\}/', '', $content);
    }

}
