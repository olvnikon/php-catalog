<?php

/**
 * @author Никонов Владимир Андреевич
 */
class Slide extends AbstractEntity
{

    public $id;
    public $url;
    public $sortOrder;

    /**
     *
     * @return string
     */
    public function getResourcePath()
    {
        return CFG_PATH_IMAGES . basename($this->url);
    }

}

class SlideManager extends AbstractManager
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
