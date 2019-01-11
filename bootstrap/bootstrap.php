<?php
use Core\Router;
use Core\Auth;
    // load configuration and other functions
    require_once ROOT . DS . 'config' . DS . 'config.php';
    require_once ROOT . DS . 'config' . DS . 'functions.php';

    // autoloading classes
    require ROOT . DS . 'vendor/autoload.php';
/* new Auth();
die(); */
    // Route the request
    try {
        Router::route($url);
    } catch (\Exception $e) {
        //header();
        echo $e->getMessage();
    }
