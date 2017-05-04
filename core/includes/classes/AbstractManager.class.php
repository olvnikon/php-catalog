<?php

/**
 * @author Никонов Владимир Андреевич
 */
abstract class AbstractManager
{

    /**
     *
     * @var \DB\API
     */
    private $_db = NULL;

    /**
     *
     * @var Object Маппинг объекта
     */
    private $_mapping = NULL;

    /**
     * Конструктор класса
     *
     * @return void
     */
    public function __construct()
    {
        $this->_initDB();
        $this->_initMapping();
    }

    /**
     * Инициализация базы данных
     *
     * @return void
     */
    private function _initDB()
    {
        require_once 'db/API.php';
        // Spike-nail!!!
        $this->_db = $GLOBALS['dbConnection'];
    }

    /**
     * Инициализация маппинга
     *
     * @return void
     */
    private function _initMapping()
    {
        $manager = get_called_class();
        $entityName = str_replace('Manager', '', $manager);
        require_once CFG_PATH_MAPPING . sprintf('%s.class.php', $entityName);
        $mapping = '_' . $entityName;
        $this->_mapping = new $mapping();
    }

    /**
     * Изменить ORDER BY в SQL запросе
     *
     * @param string $order ORDER BY в SQL запросе
     * @return void
     */
    public function setOrder($order)
    {
        $this->_mapping->order = strip_tags($order);
    }

    /**
     * Изменить GROUP BY в SQL запросе
     *
     * @param string $group GROUP BY в SQL запросе
     * @return void
     */
    public function setGroup($group)
    {
        $this->_mapping->group = strip_tags($group);
    }

    /**
     * Загрузка из базы нескольких объектов
     *
     * @param string $whereStr WHERE в SQL запросе (id = :id AND name = :name ...)
     * @param array $params Массив параметров для запроса array(':id' => 1, ':name' => 'admin' ...)
     * @param int $page Номер навигационной страницы
     * @param int $limit Количество элементов на странице
     * @return array Массив объектов
     */
    public function getAll($whereStr = '', $params = array(), $page = '',
        $limit = '')
    {
        $whereStr = empty($whereStr)
            ? ''
            : 'WHERE ' . $this->_replaceEntityFieldsByMapping($whereStr);
        $limitStr = $page !== '' && $limit !== ''
            ? 'LIMIT ' . $page * $limit . ',' . $limit
            : '';

        $sql = sprintf('SELECT * FROM `%s` %s %s %s %s',
            $this->_mapping->tableName, $whereStr, $this->_mapping->group,
            $this->_mapping->order, $limitStr);
        $results = $this->_db->selectAll($sql, $params);
        $entities = array();
        foreach ($results AS $result) {
            $entities[] = $this->_extractEntity($result);
        }

        return $entities;
    }

    /**
     *
     * @param string $whereStr
     * @return string
     */
    private function _replaceEntityFieldsByMapping($whereStr)
    {
        foreach ($this->_mapping->mapping AS $entityField => $dbField) {
            if ($dbField != 'settings' && strstr($whereStr, $entityField)) {
                $whereStr = preg_replace("/ {$entityField}|^{$entityField}/",
                    " {$dbField}", $whereStr);
            }
        }
        return $whereStr;
    }

    /**
     * Заполняет сущность, используя объект PDO
     *
     * @param PDO::FETCH_OBJ $result Объект PDO
     * @return AbstractEntity Возвращает заполенную в соответствии с маппингом сущность
     */
    private function _extractEntity($result)
    {
        $entity = new $this->_mapping->entityName();
        foreach ($this->_mapping->mapping AS $var => $field) {
            if ($this->_mapping->types[$var] == 'JSON' && !empty($result->$field)) {
                $json = json_decode($result->$field);
                $entity->$var = empty($json->$var)
                    ? ''
                    : $json->$var;
            } else {
                $entity->$var = $result->$field;
            }
        }

        return $entity;
    }

    /**
     * Посчитать количество строк
     *
     * @param string $whereStr WHERE в SQL запросе (id = :id AND name = :name ...)
     * @param array $params Массив параметров для запроса array(':id' => 1, ':name' => 'admin' ...)
     * @return int
     */
    public function getCounts($whereStr = '', $params = array())
    {
        $sql = sprintf(
            'SELECT COUNT(id) AS cnt FROM `%s` %s', $this->_mapping->tableName,
            empty($whereStr)
                ? ''
                : 'WHERE ' . $this->_replaceEntityFieldsByMapping($whereStr)
        );
        $results = $this->_db->selectOne($sql, $params);
        return intval($results->cnt);
    }

    /**
     * Загрузка одного объекта по ID
     *
     * @param $id Id объекта в базе
     * @return AbstractEntity Объект
     */
    public function getById($id)
    {
        $sql = sprintf('SELECT * FROM `%s` WHERE id=:id LIMIT 1',
            $this->_mapping->tableName);
        $result = $this->_db->selectOne($sql, array(':id' => $id));
        if (empty($result)) {
            return FALSE;
        }

        return $this->_extractEntity($result);
    }

    /**
     * Обновление объекта в базе
     *
     * @param obj $entity Объект класса, с которым работает Manager
     * @return boolean Успех обновления
     */
    public function update(AbstractEntity $entity)
    {
        if ($this->_mapping->entityName != get_class($entity)) {
            return FALSE;
        }

        $this->_setCommonVars($entity);
        list($values, $params) = $this->_fillParamsByMapping($entity);

        $sql = sprintf('UPDATE `%s` SET %s WHERE id=:id',
            $this->_mapping->tableName, implode(',', $values));
        return $this->_db->query($sql, $params);
    }

    /**
     * Автоматически заполняет переменные, которые могут быть автоматически заполнены
     *
     * @param AbstractEntity $entity
     * @return void
     */
    private function _setCommonVars(AbstractEntity $entity)
    {
        if (empty($entity->id)) {
            $this->_setNewEntityCommonVars($entity);
        }

        $this->_updateExistedEntityCommonVars($entity);
    }

    /**
     * Автоматически заполняет переменные новой сущности, которые могут быть автоматически заполнены
     *
     * @param AbstractEntity $entity
     * @return void
     */
    private function _setNewEntityCommonVars(AbstractEntity $entity)
    {
        $entity->id = NULL;
        $entity->createDate = date('Y-m-d H:i:s');
    }

    /**
     * Автоматически заполняет переменные существующей сущности, которые могут быть автоматически заполнены
     *
     * @param AbstractEntity $entity
     * @return void
     */
    private function _updateExistedEntityCommonVars(AbstractEntity $entity)
    {
        $entity->modifyDate = date('Y-m-d H:i:s');
        $user = Application::getLoggedUser();
        $entity->user = empty($user)
            ? 0
            : $user->id;
        if ($this->_needProcessSortOrder($entity)) {
            $this->_processSortOrder($entity);
        }
    }

    /**
     * Нужно ли процессить sortOrder
     *
     * @param AbstractEntity $entity
     * @return boolean
     */
    private function _needProcessSortOrder(AbstractEntity $entity)
    {
        return key_exists('sortOrder', $this->_mapping->mapping)
            && empty($entity->sortOrder);
    }

    /**
     * Автоматически посчитать sortOrder
     *
     * @param AbstractEntity $entity
     * @return void
     */
    protected function _processSortOrder(AbstractEntity $entity)
    {
        $this->setOrder('ORDER BY sort_order DESC');
        list($sql, $vars) = $this->_getSortOrderCondition($entity);
        $entities = $this->getAll($sql, $vars, 0, 1);
        $entity->sortOrder = empty($entities)
            ? 10
            : $entities[0]->sortOrder + 10;
    }

    /**
     *
     * @param AbstractEntity $entity
     * @return array
     */
    protected function _getSortOrderCondition(AbstractEntity $entity)
    {
        return array('', array());
    }

    /**
     * Подготавливает данные для SQL запроса
     * @param AbstractEntity $entity
     * @return array Массивы переменных и значений
     */
    private function _fillParamsByMapping(AbstractEntity $entity)
    {
        $values = array();
        $params = array();
        $jsons = array();
        foreach ($this->_mapping->mapping AS $var => $field) {
            if (!in_array(':' . $field, $values) && $field != 'id') {
                $values[] = empty($entity->id)
                    ? (':' . $field)
                    : ($field . '=:' . $field);
            }

            if ($this->_mapping->types[$var] == 'JSON') {
                if (empty($jsons[$field])) {
                    $jsons[$field] = new stdClass();
                }
                $jsons[$field]->$var = $entity->$var;
            } else {
                $params[':' . $field] = $entity->$var;
            }
        }

        foreach ($jsons AS $field => $options) {
            $params[':' . $field] = json_encode($options, 256);
        }

        return array($values, $params);
    }

    /**
     * Вставка объекта в базу
     *
     * @param obj $entity Объект класса, с которым работает Manager
     * @return boolean Успех сохранения
     */
    public function create(AbstractEntity $entity)
    {
        if ($this->_mapping->entityName != get_class($entity)) {
            return FALSE;
        }

        $this->_setCommonVars($entity);
        list($values, $params) = $this->_fillParamsByMapping($entity);

        $sql = sprintf('INSERT INTO `%s` VALUES (:id,%s)',
            $this->_mapping->tableName, implode(',', $values));
        $entity->id = $this->_db->insert($sql, $params);
        return $entity;
    }

    /**
     * Удаление объекта из базы по Id
     *
     * @param int $id Id объекта
     * @return boolean Успех удаления
     */
    public function delete($id)
    {
        $sql = sprintf('DELETE FROM `%s` WHERE id=:id LIMIT 1',
            $this->_mapping->tableName);
        return $this->_db->query($sql, array(':id' => $id));
    }

}
