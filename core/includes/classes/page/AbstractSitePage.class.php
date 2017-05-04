<?php

require_once CFG_PATH_DB_CLASS . 'Setting.class.php';

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractSitePage extends AbstractPage
{

    /**
     *
     * @var string
     */
    protected $_tplDir = CFG_PATH_TEMPLATE;

    /**
     *
     * @var string
     */
    protected $_tplFile = 'general.html';

    /**
     * Построить содержимое страницы
     *
     * @return void
     */
    protected function _buildContent()
    {
        $this->_parseSettings();
        $this->_parseCategoties();
        $this->_parseCommonVars();
        $this->_parseFooterArticles();

        if (self::_isSiteDisabled()) {
            $this->_parseSiteDisabled();
        } else {
            $this->_parsePageContent();
        }
    }

    /**
     * Заполнить переменные из настроек
     *
     * @return void
     */
    protected function _parseSettings()
    {
        $this->_tpl->setVar('Page-Title',
            $GLOBALS['SettingManager']->getParamValue('Название сайта (title)'));
        $this->_tpl->setVar('General-Keywords',
            $GLOBALS['SettingManager']->getParamValue('Мета-данные: ключевые слова (keywords)'));
        $this->_tpl->setVar('General-Description',
            $GLOBALS['SettingManager']->getParamValue('Мета-данные: описание (description)'));
        $this->_tpl->setVar('General-FooterText',
            $GLOBALS['SettingManager']->getParamValue('Текст подвала'));
        $this->_tpl->setVar('General-Counter',
            $GLOBALS['SettingManager']->getParamValue('Код счетчика сайта'));
        $this->_tpl->setVar('General-Email',
            $GLOBALS['SettingManager']->getParamValue('Email для получения писем с сайта'));
        $this->_tpl->setVar('General-WorkTime',
            $GLOBALS['SettingManager']->getParamValue('Время работы (шапка)'));
        $this->_tpl->setVar('General-ContactPhone',
            $GLOBALS['SettingManager']->getParamValue('Телефон для связи (шапка)'));
        $this->_tpl->setVar('General-Address',
            $GLOBALS['SettingManager']->getParamValue('Адрес (шапка)'));

        $this->_parseSocial();
    }

    /**
     * Заполнить блок с социальными сетями
     *
     * @return void
     */
    private function _parseSocial() {
        $this->_tpl->setVar('SocialBlock', '');

        $this->_parseSocialVK();
        $this->_parseSocialOK();
        $this->_parseSocialFacebook();
        $this->_parseSocialInstagram();
    }

    /**
     * Распарсить ссылку на VK
     *
     * @return void
     */
    private function _parseSocialVK() {
        $vk = $GLOBALS['SettingManager']->getParamValue('Мы в контакте');
        if (!empty($vk)) {
            $this->_tpl->setVar('Social-VK', $vk);
            $this->_tpl->parseB2V('SocialBlock', 'SOCIAL-VK', TRUE);
        }
    }

    /**
     * Распарсить ссылку на OK
     *
     * @return void
     */
    private function _parseSocialOK() {
        $ok = $GLOBALS['SettingManager']->getParamValue('Мы в однокласниках');
        if (!empty($ok)) {
            $this->_tpl->setVar('Social-OK', $ok);
            $this->_tpl->parseB2V('SocialBlock', 'SOCIAL-OK', TRUE);
        }
    }

    /**
 * Распарсить ссылку на Facebook
 *
 * @return void
 */
    private function _parseSocialFacebook() {
        $facebook = $GLOBALS['SettingManager']->getParamValue('Мы на facebook');
        if (!empty($facebook)) {
            $this->_tpl->setVar('Social-Facebook', $facebook);
            $this->_tpl->parseB2V('SocialBlock', 'SOCIAL-FACEBOOK', TRUE);
        }
    }

    /**
     * Распарсить ссылку на Instagram
     *
     * @return void
     */
    private function _parseSocialInstagram() {
        $instagram = $GLOBALS['SettingManager']->getParamValue('Мы на инстаграм');
        if (!empty($instagram)) {
            $this->_tpl->setVar('Social-Instagram', $instagram);
            $this->_tpl->parseB2V('SocialBlock', 'SOCIAL-INSTAGRAM', TRUE);
        }
    }

    /**
     * Распарсить меню
     *
     * @return void
     */
    private function _parseCategoties()
    {
        require_once 'navigation/View.php';
        $this->_tpl->setVar(
            'Navigation', Navigation\View::getHTML()
        );
    }

    /**
     * @return void
     */
    private function _parseCommonVars()
    {
        $user = Application::getLoggedUser();
        if (empty($user)) {
            $this->_tpl->parseB2V('Authorization', 'AUTHORIZATION-NO');
        } else {
            $this->_tpl->setVar('Username', $user->lastName . ' ' . $user->name);
            $this->_tpl->parseB2V('Authorization', 'AUTHORIZATION-YES');
        }
    }

    /**
     * @return void
     */
    private function _parseFooterArticles()
    {
        $groups = array(
            MENU_PLACE_INFO => 'информация',
            MENU_PLACE_WHY => 'почему стоит купить',
            MENU_PLACE_ACCOUNT => 'мой аккаунт'
        );
        $am = new ArticleManager();
        foreach ($groups AS $column => $title) {
            $tpl = new Template(CFG_PATH_TPL . 'footer_articles.html');
            $tpl->setVar('Title', $title);
            $articles = $am->getAll('parent_id=:parent_id AND state=1',
                array('parent_id' => $column));
            foreach ($articles AS $article) {
                $tpl->setVar('Caption', $article->name);
                $tpl->setVar('Url', 'article/' . $article->id);
                $tpl->parseB2V('Articles', 'ARTICLE', TRUE);
            }
            $this->_tpl->setVar('Articles-' . $column, $tpl->fillTemplate());
        }
    }

    /**
     * Отключен ли сайт?
     *
     * @return boolean
     */
    private static function _isSiteDisabled()
    {
        if (Application::getLoggedUser()
            && Application::getLoggedUser()->isAdminOrModerator()) {
            return FALSE;
        }

        $siteState = $GLOBALS['SettingManager']->getParamValue('Статус сайта');
        return empty($siteState);
    }

    /**
     * Отобразить надпись "Сайт отключен"
     *
     * @return void
     */
    private function _parseSiteDisabled()
    {
        $siteDisabled = new Template(CFG_PATH_TPL . 'site_disabled.html');
        $this->_tpl->setVar('Page-Content', $siteDisabled->fillTemplate());
    }

    /**
     * Проверка капчи
     *
     * @return boolean
     */
    protected static function _isCorrectCaptcha()
    {
        return !Session::isEmpty('captcha') && Session::get('captcha') === Request::get('captcha');
    }

}
