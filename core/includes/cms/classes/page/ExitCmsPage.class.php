<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ExitCmsPage extends AbstractPage
{

    protected function _initTpl()
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
        Application::getInstance()->security->logOut();
        Request::goToLocalPage('cms/' . \Menu\API::AUTORIZATION);
    }

}
