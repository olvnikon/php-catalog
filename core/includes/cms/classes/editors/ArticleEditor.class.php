<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class ArticleEditor extends AbstractEditor
{

    protected $_requiredFields = array('name', 'state', 'parentId');
    protected $_entityName = 'Article';

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Наименование'),
            'uploader' => array(Field::FILEUPLOADER, 'Ресурсы'),
            'content' => array(Field::TEXTEDITOR, 'Описание'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            ),
            'parentId' => array(
                Field::SELECTBOX, 'Наследование', self::_getPlaceOptions()
            ),
            'keywords' => array(Field::TEXTAREA, 'Meta: keywords'),
            'description' => array(Field::TEXTAREA, 'Meta: description')
        );
    }

}
