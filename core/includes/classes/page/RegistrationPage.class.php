<?php

/**
 * @author Никонов Владимир Андреевич
 */
class RegistrationPage extends AbstractSitePage
{

    public function process()
    {
        if (Request::isEmpty('editorSubmit')) {
            return;
        }
        $editor = new RegistrationEditor();
        $editor->process();
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $tpl = new Template(CFG_PATH_TPL . 'registration.html');
        $this->_parseStaticContent($tpl);
        $this->_showEditor($tpl);
        $this->_tpl->setVar('Page-Content', $tpl->fillTemplate());
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseStaticContent(Template $tpl)
    {
        $spm = new StaticPageManager();
        $header = $spm->getById(PAGE_TYPE_REGISTRATION_HEADER);
        $tpl->setVar('Registration-Header', $header->content);

        $paragraph = $spm->getById(PAGE_TYPE_REGISTRATION_PARAGRAPH);
        $tpl->setVar('Registration-Paragraph', $paragraph->content);
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _showEditor(Template $tpl)
    {
        $editor = new RegistrationEditor();
        $tpl->setVar('RegistrationForm', $editor->getHTML());
    }

}
