<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class SubscriptionArticleEditor extends AbstractEditor
{

    protected $_entityName = 'SubscriptionArticle';

    protected function _getControls()
    {
        return array(
            'name' => array(Field::TEXT, 'Название'),
            'content' => array(Field::TEXTEDITOR, 'Содержимое'),
        );
    }

    protected function _processSpecialFields()
    {
        if ($this->_isNewItem) {
            $this->_item->sendState = 0;
        }
    }

}
