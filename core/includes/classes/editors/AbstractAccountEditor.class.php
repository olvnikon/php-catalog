<?php

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractAccountEditor extends AbstractEditor
{

    protected $_template = 'account_editor.html';
    protected $_templateRoot = CFG_PATH_TPL;
    protected $_entityName = 'User';

    /**
     * @return void
     */
    protected function _saveToDB()
    {
        parent::_saveToDB();
        Session::set('User', serialize($this->_item));
    }

}
