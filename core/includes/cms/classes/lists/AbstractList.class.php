<?php

require_once 'lists/dictionary/Field.php';

use Lists\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractList
{

    protected $_showFilter = FALSE;
    protected $_multySelect = FALSE;
    protected $_sortable = FALSE;
    protected $_canRemove = FALSE;
    protected $_canEdit = TRUE;
    protected $_canAdd = FALSE;
    protected $_intLink = FALSE;
    protected $_fields = array();
    protected $_manager;
    protected $_template = 'table_list.html';
    protected $_tpl;

    public function __construct()
    {
        $this->_tpl = new Template(CFG_PATH_TPL_CMS . $this->_template);

        $this->_initManager();
        $this->_parseTopActions();
        $this->_initFields();
    }

    private function _initManager()
    {
        $managerClass = $this->_entityName . 'Manager';
        $this->_manager = new $managerClass();
    }

    private function _initFields()
    {
        $fields = $this->_getFields();
        foreach ($fields AS $field => $params) {
            $type = Field::getFieldTypeName($params[0]);
            $params[0] = $field;
            call_user_func_array(
                array($this, '_add' . $type), $params
            );
        }
    }

    abstract protected function _getFields();

    private function _parseTopActions()
    {
        $this->_tpl->setVar('TopActions', '');
        $this->_tpl->setVar('TopAction', '');
        if ($this->_canAdd) {
            $this->_tpl->parseB2V('TopAction', 'ADD-ACTION', TRUE);
        }

        if ($this->_canRemove && $this->_multySelect) {
            $this->_tpl->parseB2V('TopAction', 'DELETE-ACTION', TRUE);
        }

        if ($this->_sortable) {
            $this->_tpl->parseB2V('TopAction', 'SORTABLE-ACTION', TRUE);
        }

        $topAction = $this->_tpl->getVar('TopAction');
        if (!empty($topAction)) {
            $this->_tpl->parseB2V('TopActions', 'TOP-ACTIONS');
        }
    }

    private function _addCaption($fieldName, $fieldCaption, $isFilter = FALSE,
        $isSortable = FALSE)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::CAPTION,
            'isFilter' => $isFilter,
            'isSortable' => $isSortable
        );
    }

    private function _addSelect($fieldName, $fieldCaption, $options,
        $isFilter = FALSE, $isSortable = FALSE)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::SELECT,
            'isFilter' => $isFilter,
            'isSortable' => $isSortable,
            'options' => $options
        );
    }

    private function _addImageState($fieldName, $fieldCaption, $options,
        $isFilter = FALSE, $isSortable = FALSE)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::IMAGE_STATE,
            'isFilter' => $isFilter,
            'isSortable' => $isSortable,
            'options' => $options
        );
    }

    private function _addImage($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::IMAGE
        );
    }

    private function _addDate($fieldName, $fieldCaption, $isFilter = FALSE,
        $isSortable = FALSE)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::DATE,
            'isFilter' => $isFilter,
            'isSortable' => $isSortable
        );
    }

    /**
     * Получить параметры для получения объектов
     *
     * @return array
     * @todo Пока работает только для текстовых полей
     */
    public function getCondition()
    {
        if (!$this->_showFilter) {
            return array('', array());
        }

        $sqls = array();
        $vars = array();
        foreach (array_keys($this->_fields) AS $field) {
            if (Request::issetParam($field) && Request::get($field) !== '') {
                $sqls[] = "$field LIKE :$field";
                $vars[$field] = '%' . Request::get($field) . '%';
            }
        }
        return array(implode(' AND ', $sqls), $vars);
    }

    public function getHTML($items)
    {
        if ($this->_showFilter) {
            $this->_parseFilter();
        }
        $this->_parseHeaders();

        foreach ($items AS $item) {
            $this->_buildItem($item);
        }

        return $this->_tpl->fillTemplate();
    }

    private function _parseFilter()
    {
        $this->_tpl->setVar('FilterColumns', '');

        if ($this->_multySelect) {
            $this->_tpl->parseB2V('FilterColumns', 'FILTER-COLUMN', TRUE);
        }

        foreach ($this->_fields AS $field => $options) {
            $this->_tpl->setVar('FilterField', $field);
            if (empty($options['isFilter'])) {
                $this->_tpl->setVar('FilterInput', '');
            } elseif ($options['type'] == Field::CAPTION) {
                $this->_parseFilterInput($field);
            } elseif ($options['type'] == Field::DATE) {
                $this->_parseFilterDate($field);
            } elseif ($options['type'] == Field::SELECT
                || $options['type'] == Field::IMAGE_STATE) {
                $this->_parseFilterSelect($field, $options['options']);
            }
            $this->_tpl->parseB2V('FilterColumns', 'FILTER-COLUMN', TRUE);
        }

        $this->_tpl->parseB2V('Filter', 'FILTER');
    }

    private function _parseFilterInput($field)
    {
        $this->_tpl->setVar('FilterFieldValue', Request::get($field));
        $this->_tpl->parseB2V('FilterInput', 'FILTER-INPUT');
    }

    private function _parseFilterDate($field)
    {
        $this->_tpl->setVar('FilterFieldValue', Request::get($field));
        $this->_tpl->parseB2V('FilterInput', 'FILTER-DATE');
    }

    private function _parseFilterSelect($field, $options)
    {
        $fieldValue = Request::get($field);
        $this->_tpl->setVar('FilterOptions', '');
        foreach ($options AS $value => $caption) {
            if ($fieldValue !== '' && $value == $fieldValue) {
                $this->_tpl->parseB2V('FilterFieldSelected',
                    'FILTER-IS-SELECTED');
            } else {
                $this->_tpl->setVar('FilterFieldSelected', '');
            }
            $this->_tpl->setVar('FilterFieldValue', $value);
            $this->_tpl->setVar('FilterFieldCaption', $caption);
            $this->_tpl->parseB2V('FilterOptions', 'FILTER-OPTION', TRUE);
        }

        $this->_tpl->parseB2V('FilterInput', 'FILTER-SELECT');
    }

    private function _parseHeaders()
    {
        if ($this->_multySelect) {
            $this->_tpl->setVar('HeaderCaption', '');
            $this->_tpl->parseB2V('Headers', 'HEADER', TRUE);
        }

        foreach ($this->_fields AS $field => $options) {
            $this->_tpl->setVar('HeaderCaption', $options['caption']);
            $this->_tpl->parseB2V('Headers', 'HEADER', TRUE);
        }

        $this->_tpl->setVar('HeaderCaption', '');
        $this->_tpl->parseB2V('Headers', 'HEADER', TRUE);

        $this->_tpl->parseB2V('ColumnHeaders', 'HEADERS');
    }

    protected function _buildItem($item)
    {
        $this->_tpl->setVar('Fields', '');

        //Order is important!!!
        $this->_parseMultySelect($item);
        foreach ($this->_fields AS $field => $options) {
            $this->_parseField($options, $item->$field);
        }
        $this->_parseItemActions($item);

        $this->_tpl->parseB2V('Items', 'ITEM', TRUE);
    }

    /**
     *
     * @param AbstractEntity $item
     * @return void
     */
    private function _parseMultySelect($item)
    {
        if ($this->_multySelect) {
            $this->_tpl->setVar('Item-Id', $item->id);
            $this->_tpl->parseB2V('Fields', 'MULTYSELECT', TRUE);
        }
    }

    /**
     *
     * @param array $options
     * @param mixed $value
     * @return void
     */
    private function _parseField($options, $value)
    {
        switch ($options['type']) {
            case Field::CAPTION:
            case Field::DATE:
                $this->_parseCaptionField($value);
                break;
            case Field::SELECT:
                $this->_parseSelectField($options, $value);
                break;
            case Field::IMAGE_STATE:
                $this->_parseImageStateField($options, $value);
                break;
            case Field::IMAGE:
                $this->_parseImageField($options, $value);
                break;
        }
    }

    /**
     *
     * @param string $value
     * @return void
     */
    private function _parseCaptionField($value)
    {
        $this->_tpl->setVar('Field-Value',
            is_array($value)
                ? implode(', ', $value)
                : $value);
        $this->_tpl->parseB2V('Fields', 'FIELD', TRUE);
    }

    /**
     *
     * @param array $options
     * @param mixed $value
     * @return void
     */
    private function _parseSelectField($options, $value)
    {
        $this->_tpl->setVar('Field-Value', $options['options'][$value]);
        $this->_tpl->parseB2V('Fields', 'FIELD', TRUE);
    }

    /**
     *
     * @param array $options
     * @param mixed $value
     * @return void
     */
    private function _parseImageStateField($options, $value)
    {
        $this->_tpl->setVar('Field-Caption', $options['options'][$value]);
        $this->_tpl->parseB2V('Field-Image', 'STATE-' . $value);
        $this->_tpl->parseB2V('Fields', 'FIELD-STATE-IMAGE', TRUE);
    }

    /**
     *
     * @param array $options
     * @param mixed $value
     * @return void
     */
    private function _parseImageField($options, $value)
    {
        $this->_tpl->setVar('Field-Value', $value);
        $this->_tpl->setVar('Field-Caption', $options['caption']);
        $this->_tpl->parseB2V('Fields', 'FIELD-IMAGE', TRUE);
    }

    protected function _parseItemActions($item)
    {
        $this->_tpl->setVar('Action', '');
        $this->_tpl->setVar('Item-Id', $item->id);

        $this->_parseSpecialItemActions();

        if ($this->_canEdit) {
            $this->_tpl->parseB2V('Action', 'ITEM-CHANGE-ACTION', TRUE);
        }

        if ($this->_canRemove) {
            $this->_tpl->parseB2V('Action', 'ITEM-DEL-ACTION', TRUE);
        }

        if ($this->_intLink) {
            $this->_tpl->parseB2V('Action', 'ITEM-LINK-ACTION', TRUE);
        }

        $this->_tpl->parseB2V('Fields', 'ITEM-ACTIONS', TRUE);
    }

    protected function _parseSpecialItemActions()
    {

    }

    public function process()
    {
        if (Request::get('request') == 'filter') {
            $this->_processFilter();
        }
        if ($this->_sortable && Request::get('request') == 'refresh') {
            $this->_processSort();
        }
        if ($this->_canRemove && Request::get('request') == 'remove') {
            $this->_processRemove();
        }
    }

    private function _processFilter()
    {
        exit('1');
    }

    private function _processSort()
    {
        $sortItems = Request::get('sortItems');
        $sortOrder = 10;
        foreach ($sortItems AS $sortItem) {
            $item = $this->_manager->getById(intval($sortItem));
            $item->sortOrder = $sortOrder;
            $sortOrder += 10;
            $this->_manager->update($item);
        }
        exit('1');
    }

    protected function _processRemove()
    {
        $toRemove = Request::get('idItemRemove');
        $itemsToRemove = is_array($toRemove)
            ? $toRemove
            : array($toRemove);
        foreach ($itemsToRemove AS $itemToRemove) {
            $this->_manager->delete(intval($itemToRemove));
        }
        exit('1');
    }

    /**
     * Получить опции "Включено/Отключено"
     *
     * @return array
     */
    protected static function _getSelectboxOnOffOptions()
    {
        return array(0 => 'Отключено', 1 => 'Включено');
    }

    /**
     * Опции места отображения
     *
     * @return array
     */
    protected static function _getPlaceOptions()
    {
        return array(
            MENU_PLACE_INFO => 'Информация',
            MENU_PLACE_WHY => 'Почему стоит купить',
            MENU_PLACE_ACCOUNT => 'Мой аккаунт'
        );
    }

}
