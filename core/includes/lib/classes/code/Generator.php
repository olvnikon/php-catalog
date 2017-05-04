<?php

namespace Code;

class Generator
{

    /**
     *
     * @param string $prefix
     * @return string
     */
    public static function getRandomKey($prefix)
    {
        return rtrim(
            chunk_split(
                md5(
                    uniqid($prefix)
                ), 4, '-'
            ), '-'
        );
    }

}
