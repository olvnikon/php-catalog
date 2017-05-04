<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ProductPage extends AbstractProductPage
{

    /**
     * @return void
     */
    public function process()
    {
        $editor = new CommentEditor();
        $item = $this->_getItem();
        $editor->setProductId($item->id);
        $editor->process();
    }

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _prepareProductTpl()
    {
        $item = $this->_getItem();
        $this->_productTpl->setVar(
            'PageContent', ProductView::getFullHtml($item)
        );
        $this->_productTpl->setVar(
            'Comments', $this->_getCommentsHtml($item)
        );
        $this->_parsePageParamsByItem($item);
    }

    /**
     *
     * @return Product
     */
    private function _getItem()
    {
        $itemName = Request::get('name');
        $item = $this->_productManager->getAll(
            'url=:url AND state=1', array('url' => $itemName), 0, 1
        );
        if (empty($item[0])) {
            Request::goToLocalPage('index');
        }

        return ProductBuilder::patchItem($item[0]);
    }

    /**
     *
     * @param Product $item
     * @return string
     */
    private function _getCommentsHtml(Product $item)
    {
        $tpl = new Template(CFG_PATH_TPL . 'comments.html');
        $this->_parseComments($tpl, $this->_getComments($item));
        $this->_parseCommentEditor($tpl);
        return $tpl->fillTemplate();
    }

    /**
     *
     * @param Product $item
     * @return Feedback[]
     */
    private function _getComments(Product $item)
    {
        $fm = new FeedbackManager();
        return $fm->getAll('product_id=:product_id AND state=1',
                array('product_id' => $item->id), 0, 20);
    }

    /**
     *
     * @param Template $tpl
     * @param Comment[] $comments
     * @return void
     */
    private function _parseComments(Template $tpl, $comments)
    {
        $tpl->setVar('CommentsCount', count($comments));
        if (empty($comments)) {
            $tpl->parseB2V('NoComments', 'NO-COMMENTS');
        }
        foreach ($comments AS $comment) {
            $tpl->setVar('Comments', CommentView::getHtml($comment), TRUE);
        }
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseCommentEditor(Template $tpl)
    {
        $editor = new CommentEditor();
        $tpl->setVar('CommentEditor', $editor->getHTML());
    }

    /**
     *
     * @param Product $item
     * @return void
     */
    private function _parsePageParamsByItem(Product $item)
    {
        $this->_tpl->setVar('Page-Title', $item->caption);
        $this->_tpl->setVar('Page-Keywords', ', ' . $item->metaKeywords);
        $this->_tpl->setVar('Page-Description', ', ' . $item->metaDescription);
        $this->_tpl->setVar('Breadcrumbs',
            BreadcrumbsView::getHtmlByProduct($item));
    }

}
