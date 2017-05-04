<?php

abstract class AbstractFeedbackCmsPage extends AbstractSimpleCmsPage
{

    /**
     * Открыть редактор
     *
     * @return void
     */
    protected function _openEditor()
    {
        $item = $this->_manager->getById(intval(Request::get('idItem')));
        if (!empty($item)) {
            $item->readState = 1;
            $this->_manager->update($item);
        }

        $editor = $this->_getEditor($item);
        echo $editor->getHTML();
        exit();
    }

}
