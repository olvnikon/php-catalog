<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class ArticlesList extends AbstractList
{

    protected $_showFilter = TRUE;
    protected $_canRemove = TRUE;
    protected $_canAdd = TRUE;
    protected $_multySelect = TRUE;
    protected $_sortable = TRUE;
    protected $_entityName = 'Article';

    protected function _getFields()
    {
        return array(
            'name' => array(Field::CAPTION, 'Наименование', TRUE),
            'parentId' => array(Field::SELECT, 'Колонка',
                self::_getPlaceOptions(), TRUE),
            'state' => array(
                Field::SELECT, 'Статус', self::_getSelectboxOnOffOptions(), TRUE
            )
        );
    }

}
