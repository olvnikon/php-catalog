<?php

abstract class AbstractSimpleCmsPage extends AbstractCmsPage
{

    /**
     *
     * @var AbstractManager Менеджер
     */
    protected $_manager;

    /**
     *
     * @var AbstractList Лист
     */
    protected $_list;

    /**
     *
     * @var boolean
     */
    protected $_hasPaginator = TRUE;

    /**
     * Конструктор класса
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_initManager()
            ->_initList()
            ->_initBreadTrails();
    }

    /**
     * Инициализация менеджера
     *
     * @return \AbstractSimpleCmsPage
     */
    protected function _initManager()
    {
        return $this;
    }

    /**
     * Инициализация
     *
     * @return \AbstractSimpleCmsPage
     */
    protected function _initList()
    {
        return $this;
    }

    /**
     * Инициализация хлебных крошек
     *
     * @return void
     */
    protected function _initBreadTrails()
    {
        return $this;
    }

    /**
     * Переопределение метода
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        list($sql, $vars) = $this->_list->getCondition();
        if ($this->_hasPaginator) {
            $paginator = $this->_getPaginator($sql, $vars);
            $this->_tpl->setVar('Paginator', $paginator->getHTML());
            $this->_parseList($sql, $vars, $paginator);
        } else {
            $this->_parseList($sql, $vars);
        }
    }

    /**
     * Распарсить навигацию
     *
     * @param string $sql
     * @param array $vars
     * @return Paginator\API|NULL
     */
    private function _getPaginator($sql, $vars)
    {
        require_once 'paginator/API.php';
        require_once 'paginator/Options.php';
        require_once 'paginator/Painter.php';
        return new Paginator\API(
            $this->_manager->getCounts($sql, $vars), new Paginator\Options(7),
            new Paginator\Painter(
            '?' . Request::refineQuery(array('page', 'tab', 'p')) . '&',
            CFG_PATH_TPL_CMS
            ), Application::getConfigOption('ITEMS_PER_PAGE')
        );
    }

    /**
     * Распарсить лист
     *
     * @param string $sql
     * @param array $vars
     * @param Paginator\API|NULL $paginator
     * @return void
     */
    protected function _parseList($sql, $vars, $paginator = NULL)
    {
        $page = '';
        $limit = '';
        if (!empty($paginator)) {
            $page = $paginator->getStartPageIndex();
            $limit = $paginator->getItemsPerPage();
        }
        $this->_tpl->setVar(
            'ItemsList',
            $this->_list->getHTML(
                $this->_manager->getAll($sql, $vars, $page, $limit)
            )
        );
    }

    /**
     * Переопределение метода
     *
     * @return void
     */
    public function process()
    {
        if (Request::issetParam('idItem')) {
            $this->_openEditor();
        }
        if (Request::issetParam('editorSubmit')) {
            $this->_postEditor();
        }

        $this->_list->process();
    }

    /**
     * Открыть редактор
     *
     * @return void
     */
    protected function _openEditor()
    {
        try {
            $editor = $this->_getEditor(
                $this->_manager->getById(intval(Request::get('idItem')))
            );
            echo $editor->getHTML();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        exit();
    }

    /**
     * Запостить редактор
     *
     * @return void
     */
    private function _postEditor()
    {
        try {
            $editor = $this->_getEditor(
                $this->_manager->getById(intval(Request::get('item-id')))
            );
            $editor->process();
        } catch (Exception $e) {

        }
    }

}
