<?php

class BlogArticleView
{

    /**
     *
     * @param BlogArticle $article
     * @return string
     */
    public static function getShortHtml(BlogArticle $article)
    {
        $tpl = new Template(CFG_PATH_TPL . 'view/blog_short.html');
        self::_parseCommonVars($tpl, $article);
        $tpl->setVar('BlogArticle-Image', $article->image);
        $tpl->setVar('BlogArticle-ShortText', $article->contentShort);
        $tpl->setVar('BlogArticle-Url', $article->url);
        return $tpl->fillTemplate();
    }

    /**
     *
     * @param Template $tpl
     * @param BlogArticle $article
     * @return void
     */
    private static function _parseCommonVars(Template $tpl, BlogArticle $article)
    {
        $tpl->setVar('BlogArticle-Caption', $article->name);
        $tpl->setVar('BlogArticle-Time', $article->createDate);
        $tpl->setVar('BlogArticle-CategoryUrl', $article->categoryUrl);
        $tpl->setVar('BlogArticle-Category', $article->categoryName);
    }

    /**
     *
     * @param BlogArticle $article
     * @return string
     */
    public static function getFullHtml(BlogArticle $article)
    {
        $tpl = new Template(CFG_PATH_TPL . 'view/blog_full.html');
        self::_parseCommonVars($tpl, $article);
        $tpl->setVar('BlogArticle-FullText', $article->content);
        return $tpl->fillTemplate();
    }

}
