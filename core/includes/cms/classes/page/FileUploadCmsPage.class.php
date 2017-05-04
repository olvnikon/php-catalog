<?php

/**
 * @author Никонов Владимир Андреевич
 */
class FileUploadCmsPage extends AbstractPage
{

    public function __construct()
    {

    }

    public function show()
    {

    }

    protected function _buildContent()
    {

    }

    public function process()
    {
        if (!AjaxUpload::isFileUpload()) {
            echo 'error';
            exit();
        }

        $targetFile = AjaxUpload::getMovedFile();
        if (empty($targetFile)) {
            echo 'error';
            exit();
        }

        $newImage = ImageManager::processUploadImage($targetFile);
        if (!Request::isEmpty('createResource')) {
            $this->_processImage($newImage);
        }

        echo CFG_SITE_DOMAIN . $newImage;
        exit();
    }

    private function _processImage($newImage)
    {
        $image = new Resource();
        $image->url = $newImage;
        $rm = new ResourceManager();
        $rm->create($image);
    }

}
