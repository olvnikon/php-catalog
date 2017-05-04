<?php

/**
 * @author Никонов Владимир Андреевич
 */
class BlogPage extends AbstractSitePage
{

    /**
     *
     * @var \Paginator\API
     * @todo Очень плохо сделана инициализация пагинатора!
     */
    private $_paginator;

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $tpl = new Template(CFG_PATH_TPL . 'blog.html');
        if (!Request::isEmpty('category')) {
            $this->_parseCategoryArticles($tpl);
        } elseif (!Request::isEmpty('article')) {
            $this->_parseBlogArticle($tpl);
        } else {
            $this->_parseAllBlogArticles($tpl);
        }
        if (!empty($this->_paginator)
            && $this->_paginator->needShowPagination()) {
            $tpl->setVar('Paginator', $this->_paginator->getHTML());
        }
        $this->_tpl->setVar('Page-Content', $tpl->fillTemplate());
    }

    /**
     * @return void
     */
    private function _parseCategoryArticles(Template $tpl)
    {
        $bcm = new BlogCategoryManager();
        $category = $bcm->getAll('url=:url AND state=1',
            array('url' => Request::get('category')), 0, 1);
        if (empty($category)) {
            Request::goLocation('/blog');
        }
        $this->_parseBlogArticles($tpl,
            $this->_getBlogArticles($category[0]->id));
    }

    /**
     *
     * @param Template $tpl
     * @param BlogArticle[] $articles
     * @return void
     */
    private function _parseBlogArticles(Template $tpl, $articles)
    {
        foreach ($articles AS $article) {
            $tpl->setVar('PageContent',
                BlogArticleView::getShortHtml(
                    BlogArticleBuilder::patchItem($article)
                ), TRUE);
        }
    }

    /**
     *
     * @param int $categoryId
     * @return BlogArticle[]
     */
    private function _getBlogArticles($categoryId = 0)
    {
        $bm = new BlogArticleManager();
        list($sql, $vars) = $this->_getCondition($categoryId);
        $this->_paginator = $this->_getPaginator($bm->getCounts($sql, $vars));
        return $bm->getAll($sql, $vars, $this->_paginator->getStartPageIndex(),
                $this->_paginator->getItemsPerPage());
    }

    /**
     *
     * @param int $categoryId
     * @return array
     */
    private function _getCondition($categoryId)
    {
        return empty($categoryId)
            ? array('state=1', array())
            : array('state=1 AND category_id=:category_id',
            array('category_id' => $categoryId));
    }

    /**
     *
     * @param int $count
     * @return Paginator\API|NULL
     */
    private function _getPaginator($count)
    {
        require_once 'paginator/API.php';
        require_once 'paginator/Options.php';
        require_once 'paginator/Painter.php';
        return new Paginator\API(
            $count, new Paginator\Options(7),
            new Paginator\Painter(
            '?' . Request::refineQuery(array('page', 'tab', 'p')) . '&'
            ), Application::getConfigOption('ITEMS_PER_PAGE')
        );
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseBlogArticle(Template $tpl)
    {
        $bam = new BlogArticleManager();
        $article = $bam->getAll('url=:url AND state=1',
            array('url' => Request::get('article')), 0, 1);
        if (empty($article)) {
            Request::goLocation('/blog');
        }

        $tpl->setVar('PageContent',
            BlogArticleView::getFullHtml(
                BlogArticleBuilder::patchItem($article[0])
            )
        );
    }

    /**
     *
     * @param Template $tpl
     * @return void
     */
    private function _parseAllBlogArticles(Template $tpl)
    {
        $this->_parseBlogArticles($tpl, $this->_getBlogArticles());
    }

}
