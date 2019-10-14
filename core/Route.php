<?php

namespace Core;

class Route
{
	protected $routes = [
		'POST' => [],
		'GET' => []
	];

	protected $controller;

	protected $method;

	public static function get($uri, $destination)
	{
        static::insertRoutes($uri, $destination);
	}

	public static function post($uri, $destination)
	{
	    static::insertRoutes($uri, $destination);
	}

	private static function insertRoutes($uri, $destination)
    {
        $route = new static;
        $route->routes[Request::requestMethod()][$uri] = $destination;
        $requestedURI = Request::uri();

        if ($route->findURL($requestedURI, Request::requestMethod())) {
            if (empty($destination) && !isset($destination)) {
                view('index.view.php');
            }
            $route->findController($destination);
            $route->createControllerClass();
        } else {
            return false;
        }
    }

	protected function findURL($uri, $requestMethod)
	{
		if (array_key_exists($uri, $this->routes[$requestMethod])) {
			return true;
		} else {
			return false;
		}
	}

	protected function findController($destination)
	{
		$controllerClass = explode('@', $destination);
		if (is_array($controllerClass)) {
			if (strpos($controllerClass[0], 'Controller') !== false) {
		    	$this->controller = $controllerClass[0];
		    	$this->method = $controllerClass[1];
		    	return true;
			} else {
				$this->controller = $controllerClass[0] . 'Controller';
				$this->method = $controllerClass[1];
				return true;
			}
		} else {
			view($destination);
		}
	}

	private function createControllerClass()
	{
		$this->controller = "App\Http\Controllers\\" . $this->controller;
		if ($controller = new $this->controller()) {
			$method = $this->method;
			$controller->$method();
		} else {
			die("ERROR. No such class");
		}

	}

}