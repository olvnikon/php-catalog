<?php

/**
 * @author Никонов Владимир Андреевич
 */
class VerificationCode extends AbstractEntity
{

    public $id;
    public $userId;
    public $vCode;
    public $state;
    public $createDate;
    public $activationDate;
    public $endDate;

}

class VerificationCodeManager extends AbstractManager
{

    public function createByUser(User $user)
    {
        require_once 'code/Generator.php';
        $vc = new VerificationCode();
        $vc->userId = $user->id;
        $vc->state = 0;
        $vc->vCode = Code\Generator::getRandomKey($user->email);
        $vc->endDate = date('Y-m-d H:i:s', time() + 3 * 24 * 60 * 60);
        return parent::create($vc);
    }

}
