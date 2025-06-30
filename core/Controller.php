<?php
namespace Core;

class Controller
{
    protected function model($model)
    {
        $modelClass = '\\App\\Models\\' . $model;
        if (class_exists($modelClass)) {
            return new $modelClass();
        }
        return null;
    }
    
    protected function view($view, $data = [])
    {
        global $config;
        if (file_exists('app/views/' . $view . '.php')) {
            extract($data);
            require_once 'app/views/' . $view . '.php';
            return true;
        }
        return false;
    }
} 