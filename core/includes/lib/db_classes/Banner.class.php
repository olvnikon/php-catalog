<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Banner extends AbstractEntity
{

    public $id;
    public $name;
    public $content;
    public $state;
    public $createDate;
    public $modifyDate;
    public $user;

}

class BannerManager extends AbstractManager
{

}
