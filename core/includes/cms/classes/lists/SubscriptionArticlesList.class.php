<?php

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
class SubscriptionArticlesList extends AbstractList
{

    protected $_canRemove = TRUE;
    protected $_canAdd = TRUE;
    protected $_multySelect = TRUE;
    protected $_showFilter = TRUE;
    protected $_entityName = 'SubscriptionArticle';

    protected function _getFields()
    {
        return array(
            'sendState' => array(
                Field::IMAGE_STATE, 'Статус',
                array(0 => 'Новая рассылка', 1 => 'Отправлена'), TRUE
            ),
            'name' => array(Field::CAPTION, 'Название', TRUE),
            'createDate' => array(Field::CAPTION, 'Дата добавления')
        );
    }

    protected function _parseSpecialItemActions()
    {
        $this->_tpl->parseB2V('Action', 'ITEM-SEND-ACTION', TRUE);
    }

    public function process()
    {
        parent::process();
        if (Request::get('request') == 'subscription') {
            $this->_processSubscription();
        }
    }

    /**
     * @return void
     */
    private function _processSubscription()
    {
        $subscription = $this->_getSubscriptionToSend();
        $headers = $this->_getEmailHeaders();
        foreach ($this->_getSubscriptionEmails() AS $subEmail) {
            $this->_sendEmail($subscription, $subEmail->email, $headers);
        }
        foreach ($this->_getSubscriptionUsers() AS $user) {
            $this->_sendEmail($subscription, $user->email, $headers);
        }
        $subscription->sendState = 1;
        $this->_manager->update($subscription);
        exit('1');
    }

    /**
     *
     * @return SubscriptionArticle
     */
    private function _getSubscriptionToSend()
    {
        $subscription = $this->_manager->getById(
            Request::get('idItemSubscription')
        );
        if (empty($subscription)) {
            exit('0');
        }

        return $subscription;
    }

    /**
     *
     * @return User[]
     */
    private function _getSubscriptionUsers()
    {
        $um = new UserManager();
        return $um->getAll('subscription=1 AND state=1');
    }

    /**
     *
     * @return string
     */
    private function _getEmailHeaders()
    {
        require_once CFG_PATH_DB_CLASS . 'Setting.class.php';
        $title = $GLOBALS['SettingManager']->getParamValue('Название сайта (title)');
        return "Content-type: text/html; charset=UTF-8 \r\n"
            . "From: \"" . $title . "\"\r\n";
    }

    /**
     *
     * @param SubscriptionArticle $subscription
     * @param string $email
     * @param string $headers
     * @return void
     */
    private function _sendEmail(SubscriptionArticle $subscription, $email,
        $headers)
    {
        if (empty($email)) {
            return;
        }

        mb_internal_encoding('UTF-8');
        mb_send_mail(
            $email, $subscription->name, $subscription->content, $headers
        );
    }

    /**
     *
     * @return SubscriptionEmail
     */
    private function _getSubscriptionEmails()
    {
        $sem = new SubscriptionEmailManager();
        return $sem->getAll();
    }

}
