<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class StaticPageEditor extends AbstractEditor
{

    protected $_entityName = 'StaticPage';

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Название'),
            'uploader' => array(Field::FILEUPLOADER, 'Ресурсы'),
            'content' => array(Field::TEXTEDITOR, 'Содержимое'),
            'keywords' => array(Field::TEXTAREA, 'Meta: keywords'),
            'description' => array(Field::TEXTAREA, 'Meta: description')
        );
    }

}
