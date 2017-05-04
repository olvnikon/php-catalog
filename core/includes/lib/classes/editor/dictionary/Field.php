<?php

namespace Editor\Dictionary;

/**
 * @author Никонов Владимир Андреевич
 */
class Field
{

    const TEXT = 1;
    const CAPTION = 2;
    const TEXTAREA = 3;
    const TEXTEDITOR = 4;
    const SELECTBOX = 5;
    const CHECKBOXGROUP = 6;
    const FILEUPLOADER = 7;
    const IMAGE = 8;
    const IMAGEGROUP = 9;
    const HIDDEN = 10;
    const DATE = 11;
    const PRICE = 12;
    const URL = 13;
    const PASSWORD = 14;

    private static $_fieldTypeNames = array(
        self::TEXT => 'Text',
        self::CAPTION => 'Caption',
        self::TEXTAREA => 'Textarea',
        self::TEXTEDITOR => 'Texteditor',
        self::SELECTBOX => 'Selectbox',
        self::CHECKBOXGROUP => 'CheckboxGroup',
        self::FILEUPLOADER => 'FileUploader',
        self::IMAGE => 'Image',
        self::IMAGEGROUP => 'ImageGroup',
        self::HIDDEN => 'Hidden',
        self::DATE => 'Date',
        self::PRICE => 'Price',
        self::URL => 'Url',
        self::PASSWORD => 'Password',
    );

    /**
     * Get field type name by type
     *
     * @param int $type
     * @return string Name
     */
    public static function getFieldTypeName($type)
    {
        return self::$_fieldTypeNames[$type];
    }

}
