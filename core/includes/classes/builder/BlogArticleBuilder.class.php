<?php

class BlogArticleBuilder
{

    /**
     *
     * @var BlogArticleManager
     */
    private static $_manager;

    /**
     *
     * @param int $id
     * @return BlogArticle|FALSE
     */
    public static function getItem($id)
    {
        self::_initManager();
        $article = self::$_manager->getById($id);
        if (empty($article)) {
            return FALSE;
        }

        return self::patchItem($article);
    }

    /**
     * @return void
     */
    private static function _initManager()
    {
        if (empty(self::$_manager)) {
            self::$_manager = new BlogArticleManager();
        }
    }

    /**
     *
     * @param BlogArticle $article
     * @return BlogArticle
     */
    public static function patchItem(BlogArticle $article)
    {
        self::_patchCategory($article);
        self::_patchShortText($article);
        return $article;
    }

    /**
     *
     * @param BlogArticle $article
     * @return void
     */
    private static function _patchCategory(BlogArticle $article)
    {
        $bcm = new BlogCategoryManager();
        $category = $bcm->getById($article->categoryId);
        $article->categoryName = $category->name;
        $article->categoryUrl = $category->url;
    }

    /**
     *
     * @param BlogArticle $article
     * @return void
     */
    private static function _patchShortText(BlogArticle $article)
    {
        $wrapped = wordwrap(
            strip_tags($article->content), 1000, ',,,,,,');
        $parts = explode(',,,,,,', $wrapped);
        $article->contentShort = $parts[0] . '...';
    }

}
