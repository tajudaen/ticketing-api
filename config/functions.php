<?php

function dnd($item)
{
    echo '<pre>';
    var_dump($item);
    die();
    echo '</pre>';
}

function sanitize($string)
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}