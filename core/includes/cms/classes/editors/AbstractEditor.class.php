<?php

require_once 'editor/dictionary/Field.php';

use Editor\Dictionary\Field;

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractEditor
{

    protected $_isNewItem = FALSE;
    protected $_item;
    protected $_manager;
    protected $_template = 'editor.html';
    protected $_templateRoot = CFG_PATH_TPL_CMS;
    protected $_tpl;
    protected $_fields = array();
    protected $_requiredFields = array();
    protected $_entityName = '';
    protected $_formAction = '';
    protected $_createAllowed = TRUE;

    /**
     * Конструктор класса
     *
     * @param Object $item Сущность для редактирования
     * @return void
     * @throws Exception
     */
    public function __construct($item = NULL)
    {
        $this->_initItem($item);
        $this->_checkCreateAllowed();

        $this->_tpl = new Template($this->_templateRoot . $this->_template);
        $this->_initControls();
    }

    /**
     * Инициализация объекта редактирования
     *
     * @param Object $item
     * @return void
     */
    protected function _initItem($item)
    {
        $this->_isNewItem = empty($item->id);
        $this->_item = empty($item)
            ? new $this->_entityName()
            : $item;
        $managerClass = $this->_entityName . 'Manager';
        $this->_manager = new $managerClass();
    }

    /**
     * Проверить возможность создания нового объекта
     *
     * @return void
     * @throws Exception
     */
    private function _checkCreateAllowed()
    {
        if ($this->_isNewItem && !$this->_createAllowed) {
            throw new Exception('Create is not allowed!');
        }
    }

    /**
     * Инициализация контролов
     *
     * @return void
     */
    private function _initControls()
    {
        $controls = $this->_getControls();
        foreach ($controls AS $control => $params) {
            $type = Field::getFieldTypeName($params[0]);
            $params[0] = $control;
            call_user_func_array(
                array($this, '_add' . $type), $params
            );
        }

        $this->_addCommonControls();
    }

    /**
     * Получить список контролов
     *
     * @return array
     */
    abstract protected function _getControls();

    /**
     * Добавить общие поля
     *
     * @return void
     */
    private function _addCommonControls()
    {
        $this->_addCreateDate();
        $this->_addModifyDate();
        $this->_addUserCaption();
    }

    /**
     * Добавить надпись "Дата создания"
     *
     * @return void
     */
    private function _addCreateDate()
    {
        if (!property_exists($this->_entityName, 'createDate')
            || empty($this->_item->createDate)) {
            return;
        }

        $this->_addCaption('createDate', 'Дата добавления');
    }

    /**
     * Добавить надпись "Дата изменения"
     *
     * @return void
     */
    private function _addModifyDate()
    {
        if (!property_exists($this->_entityName, 'modifyDate')
            || empty($this->_item->modifyDate)) {
            return;
        }

        $this->_addCaption('modifyDate', 'Дата изменения');
    }

    /**
     * Добавить надпись "кто последний изменил"
     *
     * @return void
     */
    private function _addUserCaption()
    {
        if (!property_exists($this->_entityName, 'user')
            || empty($this->_item->user)) {
            return;
        }

        $um = new UserManager();
        $user = $um->getById($this->_item->user);
        if (!empty($user)) {
            $this->_item->userName = $user->email;
            $this->_addCaption('userName', 'Пользователь', TRUE);
        }
    }

    /**
     * Добавить текстовое поле
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addText($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption, 'type' => Field::TEXT
        );
    }

    /**
     * Добавить надпись
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @param boolean $isBold
     * @return void
     */
    protected function _addCaption($fieldName, $fieldCaption, $isBold = TRUE)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::CAPTION,
            'isBold' => $isBold
        );
    }

    /**
     * Добавить текстовую область
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addTextarea($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::TEXTAREA
        );
    }

    /**
     * Добавить текстовый редактор
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addTexteditor($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::TEXTEDITOR
        );
    }

    /**
     * Добавить селектбокс
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @param array $options
     * @return void
     */
    protected function _addSelectbox($fieldName, $fieldCaption, $options)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::SELECTBOX,
            'options' => $options
        );
    }

    /**
     * Добавить чекбокс группу
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @param array $options
     * @return void
     */
    protected function _addCheckboxGroup($fieldName, $fieldCaption, $options)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::CHECKBOXGROUP,
            'options' => $options
        );
    }

    /**
     * Добавить загрузчик ресурсов
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addFileUploader($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::FILEUPLOADER
        );
    }

    /**
     * Добавить поле "изображение"
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addImage($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::IMAGE
        );
    }

    /**
     * Добавить загрузчик нескольких изображений
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addImageGroup($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::IMAGEGROUP
        );
    }

    /**
     * Добавить скрытое поле
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addHidden($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::HIDDEN
        );
    }

    /**
     * Добавить поле даты
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addDate($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption, 'type' => Field::DATE
        );
    }

    /**
     * Добавить поле для цены
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addPrice($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption, 'type' => Field::PRICE
        );
    }

    /**
     * Добавить поле ссылки
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @param string $parentField
     * @return void
     */
    protected function _addUrl($fieldName, $fieldCaption,
        $parentField = 'caption')
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption,
            'type' => Field::URL,
            'parent' => $parentField,
            'isBold' => TRUE
        );
    }

    /**
     * Добавить текстовое поле
     *
     * @param string $fieldName
     * @param string $fieldCaption
     * @return void
     */
    protected function _addPassword($fieldName, $fieldCaption)
    {
        $this->_fields[$fieldName] = array(
            'caption' => $fieldCaption, 'type' => Field::PASSWORD
        );
    }

    /**
     * Получить HTML редактора
     *
     * @return string HTML
     */
    public function getHTML()
    {
        $this->_parseCommonVars();
        foreach ($this->_fields AS $field => $options) {
            $this->_parseField($field, $options);
        }

        return $this->_tpl->fillTemplate();
    }

    /**
     * Распарсить переменные
     *
     * @return void
     */
    private function _parseCommonVars()
    {
        $this->_tpl->setVar('RequiredFields',
            '"' . implode('","', $this->_requiredFields) . '"');
        $this->_tpl->setVar('Entity-Name', $this->_entityName);
        $this->_tpl->setVar('Item-Id', $this->_item->id);
        $this->_tpl->setVar('Fields', '');
    }

    /**
     * Распарсить поле
     *
     * @param string $field
     * @param array $options
     * @return void
     */
    private function _parseField($field, $options)
    {
        $this->_tpl->setVar('Field-Required', '');
        $this->_tpl->setVar('Field-Caption', $options['caption']);
        $this->_tpl->setVar('Field-Name', $field);

        if (in_array($field, $this->_requiredFields)) {
            $this->_tpl->parseB2V('Field-Required', 'FIELD-REQUIRED');
        }

        $this->_parseFieldByType($field, $options);
    }

    /**
     * Распарсить поля по типам
     *
     * @param string $field Имя поля
     * @param array $options Опции поля
     * @return void
     */
    protected function _parseFieldByType($field, $options)
    {
        switch ($options['type']) {
            case Field::TEXT:
            case Field::PRICE:
                $this->_parseTextField($field);
                break;
            case Field::CAPTION:
            case Field::URL:
                $this->_parseCaptionField($field, $options);
                break;
            case Field::TEXTAREA:
                $this->_parseTextareaField($field);
                break;
            case Field::TEXTEDITOR:
                $this->_parseTextEditorField($field);
                break;
            case Field::SELECTBOX:
                $this->_parseSelectboxField($field, $options);
                break;
            case Field::CHECKBOXGROUP:
                $this->_parseCheckboxGroupField($field, $options);
                break;
            case Field::FILEUPLOADER:
                $this->_parseFileUploader($field);
                break;
            case Field::IMAGE:
                $this->_parseImageField($field);
                break;
            case Field::IMAGEGROUP:
                $this->_parseImageGroupField($field);
                break;
            case Field::HIDDEN:
                $this->_parseHiddenField($field);
                break;
            case Field::DATE:
                $this->_parseDateField($field);
                break;
            case Field::PASSWORD:
                $this->_parsePasswordField($field);
                break;
        }
    }

    /**
     *
     * @param string $field
     * @return void
     */
    protected function _parseTextField($field)
    {
        $this->_tpl->setVar('Field-Value', $this->_getFieldValue($field));
        $this->_tpl->parseB2V('Fields', 'FIELD-TEXT', TRUE);
    }

    /**
     *
     * @param string $field
     * @param array $options
     * @return void
     */
    protected function _parseCaptionField($field, $options)
    {
        $this->_tpl->setVar('Field-Value', $this->_getFieldValue($field));
        $this->_tpl->parseB2V('isBold',
            $options['isBold']
                ? 'CAPTION-BOLD'
                : 'CAPTION-SIMPLE');
        $this->_tpl->parseB2V('Fields', 'FIELD-CAPTION', TRUE);
    }

    /**
     *
     * @param string $field
     * @return void
     */
    protected function _parseTextareaField($field)
    {
        $this->_tpl->setVar('Field-Value', $this->_getFieldValue($field));
        $this->_tpl->parseB2V('Fields', 'FIELD-TEXTAREA', TRUE);
    }

    /**
     *
     * @param string $field
     * @return void
     */
    protected function _parseTextEditorField($field)
    {
        $this->_tpl->setVar('Field-Value', $this->_getFieldValue($field));
        $this->_tpl->parseB2V('Fields', 'FIELD-TEXTAREA', TRUE);
        $this->_tpl->setVar('Text-Editors', '#' . $field . ',', TRUE);
    }

    /**
     *
     * @param string $field
     * @param array $options
     * @return void
     */
    protected function _parseSelectboxField($field, $options)
    {
        $this->_tpl->setVar('Field-Options', '');
        foreach ($options['options'] AS $value => $caption) {
            $this->_tpl->setVar('isSelected',
                $this->_isSelected($field, $value)
                    ? 'selected="selected"'
                    : '');
            $this->_tpl->setVar('Option-Value', $value);
            $this->_tpl->setVar('Option-Caption', $caption);
            $this->_tpl->parseB2V('Field-Options', 'FIELD-OPTION', TRUE);
        }
        $this->_tpl->parseB2V('Fields', 'FIELD-SELECTBOX', TRUE);
    }

    /**
     * Выбрана ли текущая опция
     *
     * @param string $field
     * @param string $value
     * @return boolean
     */
    private function _isSelected($field, $value)
    {
        $fieldVal = $this->_getFieldValue($field);
        return $fieldVal == $value;
    }

    /**
     *
     * @param string $field
     * @param array $options
     * @return void
     */
    protected function _parseCheckboxGroupField($field, $options)
    {
        $this->_tpl->setVar('Field-Options', '');
        foreach ($options['options'] AS $value => $caption) {
            $this->_tpl->setVar('isChecked',
                $this->_isChecked($field, $value)
                    ? 'checked="checked"'
                    : '');
            $this->_tpl->setVar('Option-Value', $value);
            $this->_tpl->setVar('Option-Caption', $caption);
            $this->_tpl->setVar('Option-Name', $field);
            $this->_tpl->parseB2V('Field-Options', 'CHECKBOXGROUP-OPTION', TRUE);
        }
        $this->_tpl->parseB2V('Fields', 'FIELD-CHECKBOXGROUP', TRUE);
    }

    /**
     * Чекнут ли текущий чекбокс
     *
     * @param string $field
     * @param string $value
     * @return boolean
     */
    private function _isChecked($field, $value)
    {
        $fieldVal = $this->_getFieldValue($field);
        if ($this->_isNewItem || !is_array($fieldVal)) {
            return FALSE;
        }

        return in_array($value, $fieldVal);
    }

    /**
     *
     * @param string $field
     * @return void
     */
    protected function _parseFileUploader($field)
    {
        $this->_tpl->setVar('Parent-Id', $field);
        $this->_tpl->setVar('Upload-Page', \Menu\API::FILE_UPLOAD);
        $this->_tpl->parseB2V('Fields', 'FILE-UPLOADER', TRUE);
    }

    /**
     *
     * @param string $field
     * @return void
     */
    protected function _parseImageField($field)
    {
        $value = $this->_getFieldValue($field);

        $this->_tpl->setVar('Field-Style', '');
        if (empty($value)) {
            $this->_tpl->parseB2V('Field-Style', 'IMAGE-STYLE');
        }
        $this->_tpl->setVar('Field-Value', $this->_getFieldValue($field));
        $this->_tpl->setVar('Upload-Page', \Menu\API::FILE_UPLOAD);
        $this->_tpl->parseB2V('Fields', 'FIELD-IMAGE', TRUE);
    }

    /**
     *
     * @param string $field
     * @return void
     */
    protected function _parseImageGroupField($field)
    {
        $this->_tpl->setVar('Parent-Id', 0);
        $this->_tpl->setVar('Upload-Page', \Menu\API::FILE_UPLOAD);
        $this->_tpl->setVar('Slides', '');

        if (!empty($this->_item->$field) && is_array($this->_item->$field)) {
            foreach ($this->_item->$field AS $slide) {
                $this->_tpl->setVar('Slide-Url', $slide);
                $this->_tpl->parseB2V('Slides', 'SLIDE', TRUE);
            }
        }
        $this->_tpl->parseB2V('Fields', 'IMAGE-GROUP', TRUE);
    }

    /**
     *
     * @param string $field
     * @return void
     */
    protected function _parseHiddenField($field)
    {
        $this->_tpl->setVar('Field-Value', $this->_getFieldValue($field));
        $this->_tpl->parseB2V('Fields', 'FIELD-HIDDEN', TRUE);
    }

    /**
     *
     * @param string $field
     * @return void
     */
    protected function _parseDateField($field)
    {
        $this->_tpl->setVar('Field-Value', $this->_getFieldValue($field));
        $this->_tpl->parseB2V('Fields', 'FIELD-DATE', TRUE);
    }

    /**
     *
     * @param string $field
     * @return void
     */
    protected function _parsePasswordField($field)
    {
        $this->_tpl->parseB2V('Fields', 'FIELD-PASSWORD', TRUE);
    }

    /**
     * Процесс редактора
     *
     * @return void
     */
    public function process()
    {
        if ($this->_isProcessed()
            && $this->_processFields()
            && $this->_checkAfterProcess()) {
            $this->_processSpecialFields();
            $this->_saveToDB();
            $this->_goAfterProcess();
        }
    }

    /**
     * Выполняется ли процесс
     *
     * @return boolean
     */
    private function _isProcessed()
    {
        return Request::get('editorSubmit') == $this->_entityName . 'Editor';
    }

    /**
     * Процесс полей
     *
     * @return boolean
     */
    private function _processFields()
    {
        foreach ($this->_fields AS $field => $options) {
            switch ($options['type']) {
                case Field::TEXTEDITOR:
                    $this->_processTextEditor($field);
                    break;
                case Field::CAPTION:
                    break;
                case Field::IMAGE:
                    $this->_processImageField($field);
                    break;
                case Field::IMAGEGROUP:
                    $this->_processImageGroup($field);
                    break;
                case Field::PRICE:
                    $this->_processPriceField($field);
                    break;
                case Field::URL:
                    $this->_processUrlField($field, $options['parent']);
                    break;
                case Field::SELECTBOX:
                default:
                    $this->_setFieldValue($field, Request::get($field));
            }

            if ($this->_emptyRequiredField($field)) {
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Процесс текстового редактора
     *
     * @param string $field
     * @return void
     */
    private function _processTextEditor($field)
    {
        $this->_setFieldValue(
            $field, stripslashes(Request::get($field))
        );
    }

    /**
     * Процесс поля "изображение"
     *
     * @param string $field
     * @return void
     */
    private function _processImageField($field)
    {
        $fieldVal = $this->_getFieldValue($field);
        if ($fieldVal != Request::get($field)) {
            @unlink(CFG_PATH_IMAGES . basename($fieldVal));
        }
        $image = Request::get($field);
        $this->_setFieldValue($field,
            empty($image)
                ? ''
                : 'images/' . basename($image));
    }

    /**
     * Процесс загрузчика нескольких изображений
     *
     * @param string $field
     * @return void
     */
    private function _processImageGroup($field)
    {
        $reqImages = Request::get('slide_url');
        $images = array();
        if (!empty($reqImages)) {
            foreach ($reqImages AS $reqImage) {
                $images[] = 'images/' . basename($reqImage);
            }
        }

        $this->_setFieldValue($field, $images);
    }

    /**
     * Процесс поля для цены
     *
     * @param string $field
     * @return void
     */
    private function _processPriceField($field)
    {
        $priceReq = Request::get($field);
        $this->_setFieldValue(
            $field,
            empty($priceReq)
                ? ''
                : money_format(
                    '%i', floatval(
                        str_replace(',', '.', $priceReq)
                    )
                )
        );
    }

    /**
     * Процесс поля для цены
     *
     * @param string $field
     * @param string $parent
     * @return void
     */
    private function _processUrlField($field, $parent)
    {
        require_once 'text/Transliteration.php';
        $this->_setFieldValue(
            $field, Text\Transliteration::convert($this->_item->$parent)
        );
    }

    /**
     * Обязательное поле пустое?
     *
     * @param string $field
     * @return boolean
     */
    private function _emptyRequiredField($field)
    {
        $fieldVal = $this->_getFieldValue($field);
        return in_array($field, $this->_requiredFields) && empty($fieldVal) && $fieldVal != 0;
    }

    /**
     * Получить значение поля
     *
     * @param string $field
     * @return mixed
     */
    private function _getFieldValue($field)
    {
        if (strpos($field, '->')) {
            $opts = explode('->', $field);
            return $this->_item->{$opts[0]}->{$opts[1]};
        }

        return isset($this->_item->{$field})
            ? $this->_item->{$field}
            : '';
    }

    /**
     * Установить значение поля
     *
     * @param string $field
     * @param mixed $value
     * @return void
     */
    protected function _setFieldValue($field, $value)
    {
        if (strpos($field, '->')) {
            $opts = explode('->', $field);
            $this->_item->{$opts[0]}->{$opts[1]} = $value;
        } else {
            $this->_item->{$field} = $value;
        }
    }

    /**
     *
     * @return boolean
     */
    protected function _checkAfterProcess()
    {
        return TRUE;
    }

    protected function _processSpecialFields()
    {

    }

    /**
     * Сохранить в базу данных
     *
     * @return void
     */
    protected function _saveToDB()
    {
        if ($this->_isNewItem) {
            $this->_manager->create($this->_item);
        } else {
            $this->_manager->update($this->_item);
        }
    }

    /**
     * @return void
     */
    protected function _goAfterProcess()
    {
        Request::goToLocalPage($_SERVER['REQUEST_URI']);
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
