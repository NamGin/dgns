<?php
namespace Core;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Register a GET route
     *
     * @param string $uri
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function get($uri, $controller, $action)
    {
        $this->routes['GET'][$uri] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Register a POST route
     *
     * @param string $uri
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function post($uri, $controller, $action)
    {
        $this->routes['POST'][$uri] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Load routes from a routes file
     *
     * @param string $file
     * @return Router
     */
    public function load($file)
    {
        $router = $this;
        require $file;
        return $this;
    }

    /**
     * Route the request
     *
     * @param string $uri
     * @param string $method
     * @return array|null
     */
    public function route($uri, $method)
    {
        if (array_key_exists($uri, $this->routes[$method])) {
            return $this->routes[$method][$uri];
        }

        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace('/{([a-zA-Z0-9_]+)}/', '([^/]+)', $route);
            $pattern = "@^{$pattern}$@D";
            
            if (preg_match($pattern, $uri, $matches)) {
                $params = [];
                preg_match_all('/{([a-zA-Z0-9_]+)}/', $route, $paramNames);
                array_shift($matches);
                
                if (isset($paramNames[1])) {
                    foreach ($paramNames[1] as $index => $name) {
                        if (isset($matches[$index])) {
                            $params[$name] = $matches[$index];
                        }
                    }
                }
                
                $handler['params'] = $params;
                return $handler;
            }
        }

        return null;
    }
} 