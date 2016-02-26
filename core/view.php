<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.02.16
 * Time: 13:32
 */
class View
{
    function generate($content, $template, $data = null)
    {
        include 'application/views/' . template;
    }
}