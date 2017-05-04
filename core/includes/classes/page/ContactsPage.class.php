<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ContactsPage extends AbstractSitePage
{

    /**
     *
     * @var ContactFeedbackEditor
     */
    private $_editor;

    public function __construct()
    {
        parent::__construct();
        $this->_editor = new ContactFeedbackEditor();
    }

    /**
     * @return void
     */
    public function process()
    {
        $this->_editor->process();
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $spm = new StaticPageManager();
        $page = $spm->getById(PAGE_TYPE_CONTACTS);

        $this->_parsePageFromDB($page);
        $this->_tpl->setVar('Page-Content', $this->_getPageContent($page));
    }

    /**
     * Заполнить переменные, используя StaticPage
     *
     * @param StaticPage $staticPage
     * @return void
     */
    private function _parsePageFromDB(StaticPage $staticPage)
    {
        $this->_tpl->setVar('Page-Keywords', ', ' . $staticPage->keywords);
        $this->_tpl->setVar('Page-Description', ', ' . $staticPage->description);
        $this->_tpl->setVar('Page-Title', 'Наши контакты');
    }

    /**
     *
     * @param StaticPage $staticPage
     * @return string
     */
    private function _getPageContent(StaticPage $staticPage)
    {
        $tpl = new Template(CFG_PATH_TPL . 'contacts.html');
        $tpl->setVar('PageContent', $staticPage->content);
        $tpl->setVar('Feedback-Editor', $this->_editor->getHTML());
        $tpl->setVar('Map-Code',
            $GLOBALS['SettingManager']->getParamValue('Код карты обратной связи'));
        $tpl->setVar('General-ContactPhone',
            $GLOBALS['SettingManager']->getParamValue('Телефон для связи (шапка)'));
        $tpl->setVar('General-Address',
            $GLOBALS['SettingManager']->getParamValue('Адрес (шапка)'));
        return $tpl->fillTemplate();
    }

}
