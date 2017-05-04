<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class CategoriesList extends AbstractList
{

    protected $_canRemove = TRUE;
    protected $_canAdd = TRUE;
    protected $_multySelect = TRUE;
    protected $_sortable = TRUE;
    protected $_showFilter = TRUE;
    protected $_entityName = 'Category';

    protected function _getFields()
    {
        return array(
            'caption' => array(Field::CAPTION, 'Наименование', TRUE),
            'state' => array(
                Field::SELECT, 'Статус', self::_getSelectboxOnOffOptions(), TRUE
            ),
            'parentId' => array(
                Field::SELECT, 'Родительская категория', $this->_getCategoryOptions(),
                TRUE
            )
        );
    }

    /**
     *
     * @return array
     */
    private function _getCategoryOptions()
    {
        $cm = new CategoryManager();
        $categories = $cm->getAll('parent_id=0');
        $options = array(0 => 'Нет категории');
        foreach ($categories AS $category) {
            $options[$category->id] = $category->caption;
        }
        return $options;
    }

}
