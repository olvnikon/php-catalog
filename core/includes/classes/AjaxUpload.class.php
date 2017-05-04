<?php

/**
 * @author Никонов Владимир Андреевич
 */
class AjaxUpload
{

    /**
     * Переменная для файла
     */
    const UPLOAD_VAR = 'upload_images';

    /**
     *
     * @return boolean Происходит ли загрузка файла
     */
    public static function isFileUpload()
    {
        return !empty($_FILES[self::UPLOAD_VAR]['name']);
    }

    /**
     * Получить имя загружаемого файла
     *
     * @return string|FALSE Имя файла
     */
    public static function getMovedFile()
    {
        $uploadedFile = CFG_PATH_TMP_IMAGES . basename($_FILES[self::UPLOAD_VAR]['name']);
        if (!move_uploaded_file($_FILES[self::UPLOAD_VAR]['tmp_name'],
                $uploadedFile)) {
            return FALSE;
        }

        $targetFile = CFG_PATH_IMAGES . md5(time() . $uploadedFile) . self::_getFileExt($uploadedFile);

        return rename($uploadedFile, $targetFile)
            ? $targetFile
            : FALSE;
    }

    /**
     * Получить расширение файла
     *
     * @param string $uploadedFile Имя файла
     * @return string Расширение файла
     */
    private static function _getFileExt($uploadedFile)
    {
        switch (exif_imagetype($uploadedFile)) {
            case IMAGETYPE_JPEG: return '.jpg';
            case IMAGETYPE_PNG: return '.png';
            case IMAGETYPE_GIF: return '.gif';
        }
    }

}
