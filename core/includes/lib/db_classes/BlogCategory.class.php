<?php

/**
 * @author Никонов Владимир Андреевич
 */
class BlogCategory extends AbstractEntity
{

    public $id;
    public $name;
    public $url;
    public $state;
    public $createDate;
    public $modifyDate;
    public $user;

}

class BlogCategoryManager extends AbstractManager
{

}
