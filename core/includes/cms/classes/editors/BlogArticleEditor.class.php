<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class BlogArticleEditor extends AbstractEditor
{

    protected $_requiredFields = array('name', 'state', 'categoryId');
    protected $_entityName = 'BlogArticle';

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Наименование'),
            'url' => array(Field::URL, 'Ссылка', 'name'),
            'image' => array(Field::IMAGE, 'Изображение'),
            'uploader' => array(Field::FILEUPLOADER, 'Ресурсы'),
            'content' => array(Field::TEXTEDITOR, 'Описание'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            ),
            'categoryId' => array(
                Field::SELECTBOX, 'Категория', self::_getCategoryOptions()
            ),
            'keywords' => array(Field::TEXTAREA, 'Ключевые слова (meta: keywords)'),
            'description' => array(Field::TEXTAREA, 'Описание (meta: description)')
        );
    }

    /**
     *
     * @return array
     */
    private static function _getCategoryOptions()
    {
        $cm = new BlogCategoryManager();
        $options = array();
        foreach ($cm->getAll() AS $category) {
            $options[$category->id] = $category->name;
        }
        return $options;
    }

}
