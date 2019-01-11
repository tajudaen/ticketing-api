<?php

namespace Core;

class Controller
{
    protected $controller;
    protected $method;
    protected $view;
    protected $model;
    protected $middleware;

    public function __construct($controller, $method)
    {
        parent::__construct();
        $this->controller = $controller;
        $this->method = $method;
    }

    public function model($model)
    {
        if (!class_exists($model)) {
            throw new \Exception("Model not found");
        } 
        return new $model;
    }
}