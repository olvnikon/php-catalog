<?php

namespace Paginator;

/**
 * @author Никонов Владимир Андреевич
 */
class Options
{

    //Current page
    private $_current_page;
    //Maximum page
    private $_maximum_page;
    //Visible pages count
    private $_visiblePagesCount;

    public function __construct($visiblePages = 11)
    {
        $this->_visiblePagesCount = $visiblePages;
    }

    /**
     * Generate array of arrays FROM and TO.
     * @return array Array os arrays FROM, TO.
     */
    public function getFromTo($maxPage, $cPage = 1)
    {
        $this->_maximum_page = $maxPage;
        $this->_current_page = $cPage;
        if ($this->_maximum_page <= 1) {
            return array(array(), array());
        }

        if ($this->_maximum_page <= $this->_visiblePagesCount) {
            return array(array(1), array($this->_maximum_page));
        }

        $from[] = 1;
        $lengthOfBlockWithoutCurPage = $this->_getRightLeftBlockLength();
        list($firstMiddlePage, $lastMiddlePage) = $this->_getCurrentFirstLastPages();

        if ($this->_isCurBlockLeft($firstMiddlePage)) {
            //Block with current page is left block
            $isRightBlock = FALSE;
            list($to[], $from[]) = $this->_getToForLeftAndFromForRightBlocks(
                $lastMiddlePage, $isRightBlock);
        } elseif ($this->_isCurBlockRight($lastMiddlePage)) {
            //Block with current page is right block
            $isRightBlock = TRUE;
            list($to[], $from[]) = $this->_getToForLeftAndFromForRightBlocks(
                $this->_getPageNumByOffsetFromEnd($firstMiddlePage),
                $isRightBlock);
        } else {
            //Block with current page is middle block
            $to[] = $lengthOfBlockWithoutCurPage;
            $from[] = $firstMiddlePage;
            $to[] = $lastMiddlePage;
            $from[] = $this->_getPageNumByOffsetFromEnd($lengthOfBlockWithoutCurPage);
        }

        //Last page always maximum
        $to[] = $this->_maximum_page;
        return array($from, $to);
    }

    /**
     * Subsctract offset from last page
     * @param int $offset Offset
     * @return int Position with offset
     */
    private function _getPageNumByOffsetFromEnd($offset)
    {
        return ($this->_maximum_page + 1) - $offset;
    }

    /**
     * Check middle block touches or intersect left block.
     * @param int $firstPage First page of middle block
     * @return boolean TRUE - touch or intersect
     */
    private function _isCurBlockLeft($firstPage)
    {
        return $firstPage <= $this->_getRightLeftBlockLength() + 1;
    }

    /**
     * Check middle block touches or intersect right block.
     * @param int $lastPage Last page of middle block
     * @return boolean TRUE - touch or intersect
     */
    private function _isCurBlockRight($lastPage)
    {
        return $lastPage >= $this->_getPageNumByOffsetFromEnd($this->_getRightLeftBlockLength() + 1);
    }

    /**
     * Get TO[] for left block and FROM[] for right block
     * @param int $lengthCurrentBlock Length of block with current page
     * @param boolean $rightBlock TRUE if block with current page is right
     * @return array TO[],FROM[]
     */
    private function _getToForLeftAndFromForRightBlocks($lengthCurrentBlock,
        $rightBlock = TRUE)
    {
        $minLengthOfCurBlock = round($this->_visiblePagesCount / 2);
        $lengthOfBlockWithCurPage = max(array(
            $lengthCurrentBlock, $minLengthOfCurBlock));
        $lengthOfBlockWithoutCurPage = $this->_visiblePagesCount - $lengthOfBlockWithCurPage;
        $to = $rightBlock
            ? $lengthOfBlockWithoutCurPage
            : $lengthOfBlockWithCurPage;
        $lengthOfRightBlock = ($rightBlock
                ? $lengthOfBlockWithCurPage
                : $lengthOfBlockWithoutCurPage);
        return array($to, $this->_getPageNumByOffsetFromEnd($lengthOfRightBlock));
    }

    /**
     * Get first and last pages of middle block
     * @return array (First page , Last page)
     */
    private function _getCurrentFirstLastPages()
    {
        $pageInMiddlePartWithoutCurrent = $this->_visiblePagesCount - 2 * $this->_getRightLeftBlockLength() - 1;
        //Range from current page to both directions.
        $range_r = (int) round($pageInMiddlePartWithoutCurrent / 2);
        $range_l = (int) ($pageInMiddlePartWithoutCurrent / 2);
        //Middle section range
        return array($this->_current_page - $range_l, $this->_current_page + $range_r);
    }

    /**
     * Get size of left and right blocks.
     * @return int Size of left and right blocks
     */
    private function _getRightLeftBlockLength()
    {
        return (int) ($this->_visiblePagesCount / 3);
    }

}
