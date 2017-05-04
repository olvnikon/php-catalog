<?php

namespace Paginator;

/**
 * @author Никонов Владимир Андреевич
 */
class Painter
{

    /**
     *
     * @var \Template
     */
    private $_tpl;

    public function __construct($link = '', $tplPath = CFG_PATH_TPL)
    {
        $this->_tpl = new \Template($tplPath . 'paginator.html');
        $this->_tpl->setVar('link',
            empty($link)
                ? '?'
                : $link);
        $this->_tpl->setVar('page_var', 'p');
    }

    /**
     * Рисует пагинатор от from1 до to1, от from2 до to2...
     * @param int $cPage Активная страница
     * @param array $from Массив FROM (from1, from2, from3...)
     * @param array $to Массив TO (to1, to2, to3...)
     * @param int $itemsCount Сколько всего элементов
     * @param int $itemsPerPage Сколько элементов на странице
     * @return string HTML
     */
    public function fillTemplate($cPage, $from, $to, $itemsCount = '',
        $itemsPerPage = '')
    {
        $this->_tpl->setVar('pagination', '');
        $this->_parseItemsNavigationParams($cPage, $itemsPerPage, $itemsCount);
        $this->_parsePreviousButton($cPage);
        $count = count($from);
        for ($i = 0; $i < $count; $i++) {
            $this->_tpl->setVar('pagination',
                $this->_generateFromTo($cPage, $from[$i], $to[$i]), TRUE);
            if ($i < $count - 1) {
                $this->_tpl->setVar('pagination', $this->_tpl->parseB('POINTS'),
                    TRUE);
            }
        }
        $maxPage = end($to);
        $this->_parseNextButton($cPage, $maxPage);
        return $this->_tpl->fillTemplate();
    }

    /**
     *
     * @param int $cPage
     * @return void
     */
    private function _parsePreviousButton($cPage)
    {
        if ($cPage > 1) {
            $this->_tpl->setVar('pagination', $this->_prevButton($cPage), TRUE);
        }
    }

    /**
     * Заполянет шаблон кнопки "Назад"
     * @param int $cPage Активная страница
     * @return string HTML
     */
    private function _prevButton($cPage)
    {
        $this->_tpl->setVar('page', $cPage - 1);
        return $this->_tpl->parseB('PREVIOUS-BUTTON');
    }

    /**
     * Генерирует навигацию от $from до $to
     * @param int $cPage Активная страница
     * @param int $from From page
     * @param int $to To page
     * @return string HTML
     * @throws Exception Бросает исключение, когда массив $from или $to заполнен неверно
     */
    private function _generateFromTo($cPage, $from, $to)
    {
        if ($from > $to) {
            throw new Exception('Something goes wrong! Internal error!');
        }
        $out = '';
        for ($i = $from; $i <= $to; $i++) {
            $out .= $this->_navigationButton($i, $i == $cPage);
        }
        return $out;
    }

    /**
     * @param int $cPage Активная страница
     * @return string HTML
     */
    private function _navigationButton($cPage, $isActive = FALSE)
    {
        $this->_tpl->setVar('page', $cPage);
        $this->_tpl->parseB2V('class',
            $isActive
                ? 'ACTIVE-CLASS'
                : 'INACTIVE-CLASS');
        return $this->_tpl->parseB('NAVIGATION-BUTTON');
    }

    /**
     *
     * @param int $cPage
     * @param int $maxPage
     * @return void
     */
    private function _parseNextButton($cPage, $maxPage)
    {
        if ($cPage < $maxPage) {
            $this->_tpl->setVar('pagination', $this->_nextButton($cPage), TRUE);
        }
    }

    /**
     * Заполянет шаблон кнопки "Вперёд"
     * @param int $cPage Активная страница
     * @return string HTML
     */
    private function _nextButton($cPage)
    {
        $this->_tpl->setVar('page', $cPage + 1);
        return $this->_tpl->parseB('NEXT-BUTTON');
    }

    /**
     *
     * @param int $cPage
     * @param int $itemsPerPage
     * @param int $itemsCount
     * @return void
     */
    private function _parseItemsNavigationParams($cPage, $itemsPerPage,
        $itemsCount)
    {
        if (empty($itemsPerPage) || empty($itemsCount)) {
            return;
        }

        $this->_tpl->setVar('from', ($cPage - 1) * $itemsPerPage + 1);

        $to = $cPage * $itemsPerPage;
        $this->_tpl->setVar('to',
            $to > $itemsCount
                ? $itemsCount
                : $to);
        $this->_tpl->setVar('items', $itemsCount);
    }

}
