<?php

class CommentView
{

    /**
     *
     * @param Feedback $comment
     * @return string
     */
    public static function getHtml(Feedback $comment)
    {
        $tpl = new Template(CFG_PATH_TPL . 'view/comment_short.html');
        $tpl->setVar('Comment-Date', $comment->createDate);
        $tpl->setVar('Comment-User', $comment->fio);
        $tpl->setVar('Comment-Content', $comment->content);
        return $tpl->fillTemplate();
    }

}
