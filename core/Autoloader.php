<?php
spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class);
    
    if (strpos($path, 'App/') === 0) {
        $path = substr($path, 4); 
        $path = 'app/' . $path . '.php';
        if (file_exists($path)) {
            require_once $path;
            return true;
        }
    } elseif (strpos($path, 'Core/') === 0) {
        $path = substr($path, 5); 
        $path = 'core/' . $path . '.php';
        if (file_exists($path)) {
            require_once $path;
            return true;
        }
    }
    
    return false;
}); 