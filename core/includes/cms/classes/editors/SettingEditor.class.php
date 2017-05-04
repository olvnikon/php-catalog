<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class SettingEditor extends AbstractEditor
{

    protected $_entityName = 'Setting';
    protected $_createAllowed = FALSE;

    /**
     * Получить контролы
     *
     * @return array
     */
    protected function _getControls()
    {
        return array(
            'paramName' => array(Field::CAPTION, 'Описание'),
            'paramValue' => $this->_getFieldByType()
        );
    }

    /**
     * Получить поле по типу
     *
     * @return array
     */
    private function _getFieldByType()
    {
        $caption = 'Данные';
        return $this->_item->type == Field::SELECTBOX
            ? array(Field::SELECTBOX, $caption, $this->_getSelectboxOptions())
            : array($this->_item->type, $caption);
    }

    /**
     * Получить опции для селектбокса
     *
     * @return array
     */
    private function _getSelectboxOptions()
    {
        return $this->_item->paramName == 'Узор сайта'
            ? self::_getOverlayOptions()
            : self::_getSelectboxOnOffOptions();
    }

    /**
     * Получить опции для селектбокса "Узор"
     *
     * @return array
     */
    private static function _getOverlayOptions()
    {
        $options = array();
        for ($i = 1; $i <= 15; $i++) {
            $val = sprintf('%02d', $i);
            $options[$val] = $val;
        }

        return $options;
    }

}
