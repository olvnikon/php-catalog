<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractResourceEditor extends AbstractEditor
{

    protected function _getControls()
    {
        return array(
            $this->_getField() => array(Field::IMAGE, $this->_getHint())
        );
    }

    /**
     *
     * @return string
     */
    protected function _getField()
    {
        return 'url';
    }

    /**
     *
     * @return string
     */
    protected function _getHint()
    {
        return 'Изображение';
    }

}
