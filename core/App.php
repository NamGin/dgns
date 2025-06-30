<?php
namespace Core;

class App
{
    protected $router;
    protected $controller;
    protected $action;
    protected $params = [];

    public function __construct()
    {
        $this->router = new Router();
        $this->router->load('config/routes.php');
        
        $this->processRoute();
    }
    
    protected function processRoute()
    {
        $uri = $this->parseUrl();
        $uri = $uri ? implode('/', $uri) : '';
        
        $method = $_SERVER['REQUEST_METHOD'];
        
        $route = $this->router->route($uri, $method);
        
        if ($route) {
            $controllerName = $route['controller'];
            $action = $route['action'];
            
            if (file_exists('app/controllers/' . $controllerName . 'Controller.php')) {
                $controllerClass = '\\App\\Controllers\\' . $controllerName . 'Controller';
                $this->controller = new $controllerClass();
                
                if (method_exists($this->controller, $action)) {
                    $this->action = $action;
                    $this->params = isset($route['params']) ? $route['params'] : [];
                    call_user_func_array([$this->controller, $this->action], array_values($this->params));
                    return;
                }
            }
        }
        $this->legacyRouting();
    }
    
    protected function legacyRouting()
    {
        $url = $this->parseUrl();
        if (isset($url[0])) {
            if (file_exists('app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            } else {
                $controllerClass = '\\App\\Controllers\\HomeController';
                $this->controller = new $controllerClass();
                $this->action = 'index';
                call_user_func_array([$this->controller, $this->action], []);
                return;
            }
        } else {
            $controllerClass = '\\App\\Controllers\\HomeController';
            $this->controller = new $controllerClass();
            $this->action = 'index';
            call_user_func_array([$this->controller, $this->action], []);
            return;
        }
        
        $controllerClass = '\\App\\Controllers\\' . $this->controller . 'Controller';
        $this->controller = new $controllerClass();
        
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->action = $url[1];
                unset($url[1]);
            } else {
                $this->action = 'index';
            }
        } else {
            $this->action = 'index';
        }
        
        $this->params = $url ? array_values($url) : [];
        
        call_user_func_array([$this->controller, $this->action], $this->params);
    }
    
    protected function parseUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        
        return [];
    }
} 