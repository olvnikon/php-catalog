<?php

namespace DB;

/**
 * @author Никонов Владимир Андреевич
 */
class API
{

    /**
     *
     * @var \PDO
     */
    private $_db = NULL;

    /**
     * Конструктор класса
     *
     * @return void
     */
    public function __construct()
    {
        $this->_db = new \PDO('mysql:host=' . _DB_addr . ';dbname=' . _DB_name
            . ';charset=UTF8', _DB_login, _DB_pass);
        $this->_db->exec('SET NAMES UTF8');
        $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Загрузить из базы несколько строк
     *
     * @param string $sql SQL запрос
     * @param array $vars Массив параметров, которые нужны, чтобы забиндить переменные в запросе. Пример: array(":id" => 2)
     * @return array Массив \PDO объектов
     */
    public function selectAll($sql, $vars)
    {
        $query = $this->_db->prepare($sql);
        $query->execute($vars);
        return $query->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Загрузить из базы одну строку
     *
     * @param string $sql SQL запрос
     * @param array $vars Массив параметров, которые нужны, чтобы забиндить переменные в запросе. Пример: array(":id" => 2)
     * @return array \PDO объект
     */
    public function selectOne($sql, $vars)
    {
        $query = $this->_db->prepare($sql);
        $query->execute($vars);
        return $query->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * SQL запрос, который необходимо выполнить
     *
     * @param string $sql SQL запрос
     * @param array $vars Массив параметров, которые нужны, чтобы забиндить переменные в запросе. Пример: array(":id" => 2)
     * @return boolean Статус выполнения запроса
     */
    public function query($sql, $vars)
    {
        $query = $this->_db->prepare($sql);
        return $query->execute($vars);
    }

    /**
     * Вставить в базу данных
     *
     * @param string $sql SQL query.
     * @param array $vars Массив параметров, которые нужны, чтобы забиндить переменные в запросе. Пример: array(":id" => 2)
     * @return int Last insert ID.
     */
    public function insert($sql, $vars)
    {
        $query = $this->_db->prepare($sql);
        $query->execute($vars);
        return $this->_db->lastInsertId();
    }

}

// Spike-nail!!!
$GLOBALS['dbConnection'] = new API();
