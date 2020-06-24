<?php

namespace App;

/**
 * Class View
 * @package App
 */
class View
{
    /**
     * Display content
     *
     * @param string $content
     */
    public static function render($content)
    {
        echo $content;
    }
}