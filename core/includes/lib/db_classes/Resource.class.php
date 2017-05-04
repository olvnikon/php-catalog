<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Resource extends AbstractEntity
{

    public $id;
    public $url;
    public $createDate;

    public function getResourcePath()
    {
        return CFG_PATH_IMAGES . basename($this->url);
    }

}

class ResourceManager extends AbstractManager
{

    /**
     *
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        $resource = $this->getById($id);
        @unlink($resource->getResourcePath());
        parent::delete($id);
    }

}
