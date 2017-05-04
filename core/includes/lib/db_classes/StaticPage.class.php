<?php

/**
 * @author Никонов Владимир Андреевич
 */
class StaticPage extends AbstractEntity
{

    public $id;
    public $name;
    public $content;
    public $keywords;
    public $description;
    public $modifyDate;
    public $user;

}

class StaticPageManager extends AbstractManager
{

}
