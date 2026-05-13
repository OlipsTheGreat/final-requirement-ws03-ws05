<?php

namespace App\Core;

class Router {
    private $routes = [];

    public function add($action, $handler) {
        $this->routes[$action] = $handler;
    }

    public function run() {
        $action = $_GET['action'] ?? 'login';
        
        if (isset($this->routes[$action])) {
            $handler = $this->routes[$action];
            list($controllerName, $method) = explode('@', $handler);
            
            $controllerClass = "App\\Controllers\\" . $controllerName;
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    die("Method $method not found in $controllerClass");
                }
            } else {
                die("Controller $controllerClass not found");
            }
        } else {
            echo "404 - Action '$action' not found.";
        }
    }
}
