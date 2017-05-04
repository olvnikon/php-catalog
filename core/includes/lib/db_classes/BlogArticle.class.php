<?php

/**
 * @author Никонов Владимир Андреевич
 */
class BlogArticle extends AbstractEntity
{

    public $id;
    public $name;
    public $url;
    public $image;
    public $content;
    public $state;
    public $categoryId;
    public $keywords;
    public $description;
    public $createDate;
    public $modifyDate;
    public $user;

}

class BlogArticleManager extends AbstractManager
{

}
