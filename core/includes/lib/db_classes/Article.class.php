<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Article extends AbstractEntity
{

    public $id;
    public $name;
    public $content;
    public $state;
    public $parentId;
    public $keywords;
    public $description;
    public $sortOrder;
    public $createDate;
    public $modifyDate;
    public $user;

}

class ArticleManager extends AbstractManager
{

}
