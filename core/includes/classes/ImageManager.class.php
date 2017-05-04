<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ImageManager
{

    /**
     * Загрузить файл
     *
     * @param string $targetFile Имя загружаемого файла
     * @return string Загруженный файл
     */
    public static function processUploadImage($targetFile)
    {
        list($width, $height, $type) = getimagesize($targetFile);

        if ($width > IMAGE_MAX_WIDTH || $height > IMAGE_MAX_HEIGHT) {
            self::_resize($targetFile, $width, $height, $type);
        }

        chmod($targetFile, 0777);
        return 'images/' . basename($targetFile);
    }

    /**
     * Изменить размер картинки
     *
     * @param string $targetFile Имя загружаемой картинки
     * @param int $width Ширина картинки
     * @param int $height Высота картинки
     * @param int $type Тип картинки
     * @return void
     */
    private static function _resize($targetFile, $width, $height, $type)
    {
        list($newwidth, $newheight) = self::_getNewImageSize($width, $height);

        $source = self::_getImageSource($targetFile, $type);

        $target = imagecreatetruecolor($newwidth, $newheight);
        self::_addAlpha($target, $type);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $newwidth, $newheight,
            $width, $height);

        self::_createImage($targetFile, $target, $type);
        imagedestroy($target);
        imagedestroy($source);
    }

    /**
     * Получить новый рамер картинки
     *
     * @param int $width Ширина картинки
     * @param int $height Высота картинки
     * @return int[] Новый размер картинки
     */
    private static function _getNewImageSize($width, $height)
    {
        if ($height > $width) {
            $newheight = IMAGE_MAX_HEIGHT;
            $newwidth = $width / ($height / $newheight);
        } else {
            $newwidth = IMAGE_MAX_WIDTH;
            $newheight = $height / ($width / $newwidth);
        }

        return array($newwidth, $newheight);
    }

    /**
     * Получить ресурс новой картинки
     *
     * @param string $targetFile Имя загружаемого файла
     * @param int $type Тип картинки
     * @return resourse
     */
    private static function _getImageSource($targetFile, $type)
    {
        switch ($type) {
            case IMAGETYPE_GIF: return imagecreatefromgif($targetFile);
            case IMAGETYPE_JPEG: return imagecreatefromjpeg($targetFile);
            case IMAGETYPE_PNG: return imagecreatefrompng($targetFile);
        }
    }

    /**
     * Добавить прозрачность
     *
     * @param resourse $target Новая картинка
     * @param int $type Тип картинки
     * @return void
     */
    private static function _addAlpha($target, $type)
    {
        if ($type == IMAGETYPE_PNG) {
            imageAlphaBlending($target, FALSE);
            imagesavealpha($target, TRUE);
        }
    }

    /**
     * Создать картинку
     *
     * @param string $targetFile Имя загружаемого файла
     * @param resourse $target Новая картинка
     * @param int $type Тип картинки
     * @return void
     */
    private static function _createImage($targetFile, $target, $type)
    {
        switch ($type) {
            case IMAGETYPE_GIF: imagegif($target, $targetFile, 100); break;
            case IMAGETYPE_JPEG: imagejpeg($target, $targetFile, 100); break;
            case IMAGETYPE_PNG: imagepng($target, $targetFile); break;
        }
    }

}
