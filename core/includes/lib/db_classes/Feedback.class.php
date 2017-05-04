<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Feedback extends AbstractEntity
{

    public $id;
    public $fio;
    public $email;
    public $state = 0;
    public $productId;
    public $content;
    public $readState = 0;
    public $createDate;
    public $modifyDate;
    public $user;

}

class FeedbackManager extends AbstractManager
{

}
