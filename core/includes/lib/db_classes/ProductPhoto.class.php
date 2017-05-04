<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ProductPhoto extends AbstractEntity
{

    public $id;
    public $fileName;
    public $productId;
    public $sortOrder;
    public $createDate;
    public $modifyDate;
    public $user;

}

class ProductPhotoManager extends AbstractManager
{

    public function deletePhoto(ProductPhoto $photo)
    {
        unlink(CFG_PATH_IMAGES . basename($photo->fileName));
        parent::delete($photo->id);
    }

    /**
     *
     * @param AbstractEntity $entity
     * @return array
     */
    protected function _getSortOrderCondition(AbstractEntity $entity)
    {
        return array('product_id=:product_id',
            array('product_id' => $entity->productId));
    }

}
