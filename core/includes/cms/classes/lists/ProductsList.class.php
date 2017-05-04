<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class ProductsList extends AbstractList
{

    protected $_canRemove = TRUE;
    protected $_canAdd = TRUE;
    protected $_multySelect = TRUE;
    protected $_sortable = TRUE;
    protected $_showFilter = TRUE;
    protected $_entityName = 'Product';

    protected function _getFields()
    {
        return array(
            'caption' => array(Field::CAPTION, 'Наименование', TRUE),
            'category' => array(
                Field::SELECT, 'Категория', $this->_getCategoryOptions(), TRUE
            ),
            'price' => array(Field::CAPTION, 'Цена'),
            'state' => array(
                Field::SELECT, 'Статус', self::_getSelectboxOnOffOptions(), TRUE
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
        $categories = $cm->getAll();
        $options = array();
        foreach ($categories AS $category) {
            $options[$category->id] = $category->caption;
        }
        return $options;
    }

}
