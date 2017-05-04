<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class BlogArticlesList extends AbstractList
{

    protected $_canRemove = TRUE;
    protected $_canAdd = TRUE;
    protected $_multySelect = TRUE;
    protected $_sortable = FALSE;
    protected $_entityName = 'BlogArticle';

    protected function _getFields()
    {
        return array(
            'name' => array(Field::CAPTION, 'Наименование'),
            'state' => array(
                Field::SELECT, 'Статус', self::_getSelectboxOnOffOptions()
            )
        );
    }

}
