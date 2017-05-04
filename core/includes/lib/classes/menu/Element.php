<?php

namespace Menu;

/**
 * @author Никонов Владимир Андреевич
 */
class Element
{

    public $caption = '';
    public $page = '';
    public $isService;
    public $children = array();

    /**
     *
     * @param string $caption
     * @param string $page
     * @param boolean $isService
     * @return void
     */
    public function __construct($caption, $page, $isService = FALSE)
    {
        $this->caption = $caption;
        $this->page = $page;
        $this->isService = $isService;
    }

    /**
     *
     * @param \Menu\Element $child
     * @return void
     */
    public function addChild(Element $child)
    {
        $child->parentPage = $this->page;
        $this->children[] = $child;
    }

}
