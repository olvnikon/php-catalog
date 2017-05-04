<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ContactFeedback extends AbstractEntity
{

    public $id;
    public $name;
    public $email;
    public $phone;
    public $content;
    public $readState = 0;
    public $createDate;

}

class ContactFeedbackManager extends AbstractManager
{

}
