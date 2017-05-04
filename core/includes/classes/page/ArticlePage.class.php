<?php

/**
 * @author Никонов Владимир Андреевич
 */
class ArticlePage extends AbstractSitePage
{

    /**
     * Наполнить содержимое страницы
     *
     * @return void
     */
    protected function _parsePageContent()
    {
        $am = new ArticleManager();
        $article = $am->getById(intval(Request::get('id')));
        if (empty($article) || $article->state == 0) {
            Request::goToLocalPage();
        }

        $this->_parsePageFromDB($article);
    }

    /**
     * Заполнить переменные, используя Article
     *
     * @param Article $article
     * @return void
     */
    private function _parsePageFromDB(Article $article)
    {
        $this->_tpl->setVar('Page-Keywords', ', ' . $article->keywords);
        $this->_tpl->setVar('Page-Title', $article->name);
        $this->_tpl->setVar('Page-Content', $article->content, TRUE);
    }

}
