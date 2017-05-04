<?php

namespace Paginator;

/**
 * @author Никонов Владимир Андреевич
 */
class API
{

    /**
     *
     * @var \Paginator\Options
     */
    private $_paginator = NULL;

    /**
     *
     * @var \Paginator\Painter
     */
    private $_painter = NULL;

    /**
     *
     * @var int
     */
    private $_maximumPage = 1;

    /**
     *
     * @var int
     */
    private $_itemsPerPage;

    /**
     *
     * @var int
     */
    private $_itemsCount;

    /**
     *
     * @var int
     */
    private $_currentPage = 1;

    /**
     *
     * @param int $countItems
     * @param \Paginator\Options $paginator
     * @param \Paginator\Painter $painter
     * @param int $itemsPerPage
     */
    public function __construct($countItems, Options $paginator,
        Painter $painter, $itemsPerPage = 10)
    {
        $this->_itemsPerPage = $itemsPerPage;
        $this->_paginator = $paginator;
        $this->_painter = $painter;
        $this->_itemsCount = $countItems;

        $this->_maximumPage = $countItems % $this->_itemsPerPage == 0
            ? ($countItems / $this->_itemsPerPage)
            : sprintf('%d', ($countItems / $this->_itemsPerPage) + 1);
        $this->_currentPage = filter_var(
            \Request::get('p'), FILTER_VALIDATE_INT,
            array('options' => array('default' => 1, 'min_range' => 1, 'max_range' => $this->_maximumPage))
        );
    }

    /**
     *
     * @return string
     */
    public function getHTML()
    {
        list($from, $to) = $this->_paginator->getFromTo($this->_maximumPage,
            $this->_currentPage);
        return $this->_painter->fillTemplate(
                $this->_currentPage, $from, $to, $this->_itemsCount,
                $this->_itemsPerPage
        );
    }

    /**
     *
     * @return int
     */
    public function getStartPageIndex()
    {
        return $this->_currentPage - 1;
    }

    /**
     *
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->_itemsPerPage;
    }

    /**
     *
     * @return boolean
     */
    public function needShowPagination()
    {
        return $this->_maximumPage > 1;
    }

}
