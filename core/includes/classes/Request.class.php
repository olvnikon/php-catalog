<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Request
{

    /**
     * Получить переменную из запроса
     *
     * @param string $var Имя переменной
     * @return mixed Значение переменной
     */
    public static function get($var)
    {
        return isset($_REQUEST[$var])
            ? $_REQUEST[$var]
            : '';
    }

    /**
     * Установить значение переменной в запросе
     *
     * @param string $var Переменная в запросе
     * @param mixed $value Значение переменной
     * @return void
     */
    public static function set($var, $value)
    {
        $_REQUEST[$var] = $value;
    }

    /**
     * Проверяет наличие значения переменной в запросе
     *
     * @param string $var Имя переменной
     * @return boolean Пуста ли переменная
     */
    public static function isEmpty($var)
    {
        return empty($_REQUEST[$var]);
    }

    /**
     * Проверяет наличие переменной в запросе
     *
     * @param string $var Имя переменной
     * @return boolean Пуста ли переменная
     */
    public static function issetParam($var)
    {
        return isset($_REQUEST[$var]);
    }

    /**
     * Переход на страницу
     *
     * @param string $page Полный Url страницы
     * @return void
     */
    public static function goLocation($page)
    {
        header('Location: ' . $page);
        exit;
    }

    /**
     * Переход на страницу внутри сайта
     *
     * @param string $page Url страницы
     * @return void
     */
    public static function goToLocalPage($page = '')
    {
        header('Location: ' . CFG_SITE_DOMAIN . ltrim($page, '/'));
        exit;
    }

    /**
     * Удалить переменные из запроса и получить новую строку запроса
     *
     * @param array $toRemove Переменные для удаления
     * @return string Строка запроса
     */
    public static function refineQuery($toRemove = array())
    {
        $request = $_REQUEST;
        foreach ($toRemove AS $var) {
            unset($request[$var]);
        }

        return http_build_query($request);
    }

    /**
     *
     * @return boolean
     */
    public static function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

}
