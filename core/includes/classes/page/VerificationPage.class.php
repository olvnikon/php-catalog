<?php

/**
 * @author Никонов Владимир Андреевич
 */
class VerificationPage extends AbstractSitePage
{

    public function process()
    {
        if (!Request::isEmpty('state')) {
            return;
        }

        if (Request::isEmpty('code')) {
            Request::goToLocalPage('not_found.html');
        }

        $vcm = new VerificationCodeManager();
        $code = $vcm->getAll('v_code=:v_code',
            array('v_code' => Request::get('code')), 0, 1);
        if (empty($code) || $code[0]->state == 1) {
            Request::goToLocalPage('not_found.html');
        }

        $this->_activateUser($code[0], $vcm);
        Request::goToLocalPage('/verification/success');
    }

    /**
     *
     * @param VerificationCode $code
     * @param VerificationCodeManager $vcm
     * @return void
     */
    private function _activateUser(VerificationCode $code,
        VerificationCodeManager $vcm)
    {
        $um = new UserManager();
        $user = $um->getById($code->userId);
        $user->state = 1;
        $um->update($user);

        $code->activationDate = date('Y-m-d H:i:s');
        $code->state = 1;
        $vcm->update($code);
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $tpl = new Template(CFG_PATH_TPL . 'verification_success.html');
        $this->_tpl->setVar('Page-Content', $tpl->fillTemplate());
    }

}
