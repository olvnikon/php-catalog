<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class CategoryEditor extends AbstractEditor
{

    protected $_requiredFields = array('caption', 'state', 'parentId');
    protected $_entityName = 'Category';

    protected function _getControls()
    {
        return array(
            'caption' => array(Field::TEXT, 'Наименование'),
            'url' => array(Field::URL, 'Ссылка'),
            'image' => array(Field::IMAGE, 'Изображение'),
            'state' => array(
                Field::SELECTBOX, 'Статус', self::_getSelectboxOnOffOptions()
            ),
            'featured' => array(
                Field::SELECTBOX, 'На главной странице',
                self::_getSelectboxOnOffOptions()
            ),
            'parentId' => array(
                Field::SELECTBOX, 'Категория', $this->_getCategoryOptions()
            ),
            'metaKeywords' => array(Field::TEXTAREA, 'Meta: keywords'),
            'metaDescription' => array(Field::TEXTAREA, 'Meta: description')
        );
    }

    /**
     *
     * @return array
     */
    private function _getCategoryOptions()
    {
        $cm = new CategoryManager();
        $categories = $cm->getAll(
            'parent_id=0 AND state=1 AND id!=:id',
            array(':id' => $this->_item->id)
        );
        $options = array(0 => 'Главное меню');
        foreach ($categories AS $category) {
            $options[$category->id] = $category->caption;
        }
        return $options;
    }

    /**
     * @return void
     */
    protected function _processSpecialFields()
    {
        if (empty($this->_item->image) && $this->_item->featured) {
            $this->_item->featured = 0;
        }
    }

}
