<?php

/**
 * @author Никонов Владимир Андреевич
 */
class SlideEditor extends AbstractResourceEditor
{

    protected $_entityName = 'Slide';

    /**
     *
     * @return string
     */
    protected function _getHint()
    {
        return 'Фотография слайда';
    }

}
