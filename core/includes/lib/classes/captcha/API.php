<?php

namespace Captcha;

class API
{

    /**
     *
     * @var int Количество символов
     */
    private static $_count = 5;

    /**
     *
     * @var int Ширина картинки
     */
    private static $_width = 100;

    /**
     *
     * @var int Высота картинки
     */
    private static $_height = 48;

    /**
     *
     * @var int Минимальная высота символа
     */
    private static $_fontSizeMin = 27;

    /**
     *
     * @var int Максимальная высота символа
     */
    private static $_fontSizeMax = 32;

    /**
     *
     * @var string Путь к файлу относительно класса
     */
    private static $_fontFile = 'captcha/Comic_Sans_MS.ttf';

    /**
     *
     * @var int Максимальный наклон символа влево
     */
    private static $_charAngleMin = -10;

    /**
     *
     * @var int Максимальный наклон символа вправо
     */
    private static $_charAngleMax = 10;

    /**
     *
     * @var int Размер тени
     */
    private static $_charAngleShadow = 5;

    /**
     *
     * @var int Выравнивание символа по-вертикали
     */
    private static $_charAlign = 40;

    /**
     *
     * @var int Позиция первого символа по-горизонтали
     */
    private static $_start = 5;

    /**
     *
     * @var int Интервал между началами символов
     */
    private static $_interval = 16;

    /**
     *
     * @var string Набор символов
     */
    private static $_chars = '0123456789';

    /**
     *
     * @var int[] Цвет заднего фона
     */
    private static $_bg = array(255, 255, 255);

    /**
     *
     * @var int[] Цвет тени
     */
    private static $_shadow = array(32, 64, 96);

    /**
     *
     * @var int Уровень шума
     */
    private static $_noise = 10;

    public static function showCaptcha()
    {
        $image = imagecreatetruecolor(self::$_width, self::$_height);

        $backgroundColor = imagecolorallocate(
            $image, self::$_bg[0], self::$_bg[1], self::$_bg[2]
        );
        imagefill($image, 0, 0, $backgroundColor);

        self::_drawAndSaveString($image, $backgroundColor);

        if (self::$_noise) {
            self::_addNoise($image);
        }

        self::_showImage($image);
    }

    private static function _drawAndSaveString($image, $backgroundColor)
    {
        $str = '';
        $numChars = strlen(self::$_chars);
        $fontColor = imagecolorallocate(
            $image, self::$_shadow[0], self::$_shadow[1], self::$_shadow[2]
        );

        for ($i = 0; $i < self::$_count; $i++) {
            $char = self::$_chars[rand(0, $numChars - 1)];
            $fontSize = rand(self::$_fontSizeMin, self::$_fontSizeMax);
            $charAngle = rand(self::$_charAngleMin, self::$_charAngleMax);
            imagettftext($image, $fontSize, $charAngle, self::$_start,
                self::$_charAlign, $fontColor,
                CFG_PATH_LIB_CLASS . self::$_fontFile, $char);
            imagettftext($image, $fontSize,
                $charAngle + self::$_charAngleShadow * (rand(0, 1) * 2 - 1),
                self::$_start, self::$_charAlign, $backgroundColor,
                CFG_PATH_LIB_CLASS . self::$_fontFile, $char);
            self::$_start += self::$_interval;
            $str .= $char;
        }

        $_SESSION['captcha'] = $str;
    }

    private static function _addNoise($image)
    {
        for ($i = 0; $i < self::$_width; $i++) {
            for ($j = 0; $j < self::$_height; $j++) {
                imagesetpixel(
                    $image, $i, $j, self::_getNoiseColor($image, $i, $j)
                );
            }
        }
    }

    private static function _getNoiseColor($image, $i, $j)
    {
        $rgb = imagecolorat($image, $i, $j);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        $k = rand(-self::$_noise, self::$_noise);

        return imagecolorallocate(
            $image, self::_getRedNoise($r, $k), self::_getGreenNoise($g, $k),
            self::_getBlueNoise($b, $k)
        );
    }

    private static function _getRedNoise($r, $k)
    {
        $rn = $r + 255 * $k / 100;
        return $rn < 0
            ? 0
            : ($rn > 255
                ? 255
                : $rn);
    }

    private static function _getGreenNoise($g, $k)
    {
        $gn = $g + 255 * $k / 100;
        return $gn < 0
            ? 0
            : ($gn > 255
                ? 255
                : $gn);
    }

    private static function _getBlueNoise($b, $k)
    {
        $bn = $b + 255 * $k / 100;
        return $bn < 0
            ? 0
            : ($bn > 255
                ? 255
                : $bn);
    }

    private static function _showImage($image)
    {
        if (function_exists("imagepng")) {
            header("Content-type: image/png");
            imagepng($image);
        } elseif (function_exists("imagegif")) {
            header("Content-type: image/gif");
            imagegif($image);
        } elseif (function_exists("imagejpeg")) {
            header("Content-type: image/jpeg");
            imagejpeg($image);
        }

        imagedestroy($image);
    }

}
