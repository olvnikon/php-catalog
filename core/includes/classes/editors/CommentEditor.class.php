<?php

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class CommentEditor extends AbstractEditor
{

    protected $_requiredFields = array(
        'fio', 'email', 'content'
    );
    protected $_template = 'comment_editor.html';
    protected $_templateRoot = CFG_PATH_TPL;
    protected $_entityName = 'Feedback';
    private $_productId;

    /**
     *
     * @param int $productId
     * @return void
     */
    public function setProductId($productId)
    {
        $this->_productId = $productId;
    }

    protected function _getControls()
    {
        return array(
            'fio' => array(Field::TEXT, 'ФИО'),
            'email' => array(Field::TEXT, 'Ваш Email'),
            'content' => array(Field::TEXTAREA, 'Комментарий')
        );
    }

    public function getHTML()
    {
        if (!Request::isEmpty('success')) {
            $this->_tpl->parseB2V('Success', 'SUCCESS');
        }
        return parent::getHTML();
    }

    protected function _processSpecialFields()
    {
        $this->_item->productId = $this->_productId;
    }

    protected function _goAfterProcess()
    {
        Request::goToLocalPage($_SERVER['REQUEST_URI'] . '?success=1');
    }

    protected function _setFieldValue($field, $value)
    {
        parent::_setFieldValue($field, strip_tags($value));
    }

}
