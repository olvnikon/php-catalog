<?php

/**
 * @author Никонов Владимир Андреевич
 */
class SubscriptionArticle extends AbstractEntity
{

    public $id;
    public $name;
    public $content;
    public $sendState;
    public $createDate;
    public $modifyDate;
    public $user;

}

class SubscriptionArticleManager extends AbstractManager
{

}
