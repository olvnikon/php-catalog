<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Setting extends AbstractEntity
{

    public $id;
    public $paramName;
    public $paramValue;
    public $modifyDate;
    public $type;
    public $user;
    public $sortOrder;

}

class SettingManager extends AbstractManager
{

    /**
     *
     * @param string $paramName
     * @return mixed
     */
    public function getParamValue($paramName)
    {
        $value = $this->getAll(
            'param_name=:param_name', array(':param_name' => $paramName)
        );
        return empty($value)
            ? ''
            : $value[0]->paramValue;
    }

}

$GLOBALS['SettingManager'] = new SettingManager();
