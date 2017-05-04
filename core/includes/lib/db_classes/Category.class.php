<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Category extends AbstractEntity
{

    public $id;
    public $caption;
    public $url;
    public $state;
    public $parentId;
    public $featured;
    public $sortOrder;
    public $metaKeywords;
    public $metaDescription;
    public $image;
    public $createDate;
    public $modifyDate;
    public $user;

}

class CategoryManager extends AbstractManager
{

}
