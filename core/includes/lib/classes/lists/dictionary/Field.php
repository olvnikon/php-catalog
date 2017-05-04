<?php

namespace Lists\Dictionary;

/**
 * @author Никонов Владимир Андреевич
 */
class Field
{

    const CAPTION = 1;
    const SELECT = 2;
    const IMAGE_STATE = 3;
    const IMAGE = 4;
    const DATE = 5;

    private static $_fieldTypeNames = array(
        self::CAPTION => 'Caption',
        self::SELECT => 'Select',
        self::IMAGE_STATE => 'ImageState',
        self::IMAGE => 'Image',
        self::DATE => 'Date',
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
