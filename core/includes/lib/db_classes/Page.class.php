<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Page extends AbstractEntity
{

    public $id;
    public $name;
    public $class;

}

class PageManager extends AbstractManager
{

    /**
     * Получить страницу по её имени
     *
     * @param string $name
     * @return Page|NULL
     */
    public function getPageByName($name, $isCMS = FALSE)
    {
        $page = $this->getAll(
            'name=:name AND is_cms=:isCMS',
            array(':name' => $name, ':isCMS' => intval($isCMS)), 0, 1
        );
        return empty($page)
            ? NULL
            : $page[0];
    }

}
