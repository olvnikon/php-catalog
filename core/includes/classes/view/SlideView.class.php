<?php

/**
 * @author Никонов Владимир Андреевич
 */
class SlideView
{

    /**
     * Show slides
     *
     * @param Template $tpl
     * @param Slide[] $slides
     * @return string HTML
     */
    public static function getHTML(Template $tpl, $slides)
    {
        if (empty($slides)) {
            return '';
        }

        $isFirstSlide = TRUE;
        foreach ($slides AS $slide) {
            $tpl->setVar('Slide-Img', $slide->url);
            if ($isFirstSlide) {
                $tpl->parseB2V('Slides', 'ACTIVE-SLIDE', TRUE);
                $isFirstSlide = FALSE;
            } else {
                $tpl->parseB2V('Slides', 'SLIDE', TRUE);
            }
        }

        return $tpl->fillTemplate();
    }

}
