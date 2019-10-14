<?php


namespace Core\Route;


use Core\Request;
use ReflectionException;
use ReflectionMethod;
use Exception;

class Route
{
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PATCH' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    /**
     * @param $uri
     * @param $destination
     */
    public static function get($uri, $destination)
    {
        static::insertRoutes($uri, $destination);
    }

    /**
     * @param $uri
     * @param $destination
     */
    public static function post($uri, $destination)
    {
        static::insertRoutes($uri, $destination);
    }

    /**
     * @param $uri
     * @return string
     */
    private function refactorUri($uri)
    {
        if (empty($uri)) {
            return '/';
        } else {
            return $uri;
        }
    }

    /**
     * @param $uri
     * @param $destination
     */
    private static function insertRoutes($uri, $destination)
    {
        $route = new static;

        $uri = $route->refactorUri($uri);

        $requestedMethod = Request::requestMethod();

        $route->routes[$requestedMethod][$uri] = $destination;

        $requestedURI = Request::uri();

        if (is_array($destination) | is_string($destination)) {
            if ($route->findURL($requestedURI, $requestedMethod)) {
                $route->build($destination);
            }
        } else {
            $destination->__invoke();
        }


    }

    /**
     * @param $destination
     * @throws Exception
     */
    private function build($destination)
    {
        if (! is_array($destination)) {
            $controllerName = $this->findControllerName($destination);
            $methodName = $this->findMethodName($destination);
        } else {
            $controllerName = $this->findControllerName($destination);
            $methodName = $this->findMethodName($destination['uses']);
        }

        $controllerObject = $this->createObject($controllerName);


        $methodsInTheClass = $this->findMethodsInTheClass($controllerName);

        $methodReadyToCall = $this->doesMethodExist($methodName, $methodsInTheClass);

        $methodParams = $this->findMethodArgs($controllerName, $methodReadyToCall);

        $this->callMethod($controllerObject, $methodParams);
    }

    /**
     * @param $target
     * @return string
     */
    private function findControllerName($target)
    {
        if (is_array($target)) {
            $nameSpace = $target['namespace'];
            $controllerName = explode('@', $target['uses'])[0];

            $controller = $nameSpace . $controllerName;
            return $controller;
        }

        $nameSpace = $this->ControllerNameSpace();
        $controllerName = explode('@', $target)[0];

        $controller = $nameSpace . $controllerName;
        return $controller;
    }

    /**
     * @param $string
     * @return mixed
     */
    private function findMethodName($string)
    {
        return explode('@', $string)[1];
    }

    /**
     * @param $methodName
     * @param $methodsInTheClass
     * @return mixed
     * @throws Exception
     */
    private function doesMethodExist($methodName, $methodsInTheClass)
    {
        if (in_array($methodName, $methodsInTheClass)) {
            return $methodName;
        }

        throw new Exception('No Such Method');
    }

    /**
     * @param $controllerName
     * @return array
     */
    private function findMethodsInTheClass($controllerName)
    {
        $methods = get_class_methods($controllerName);

        return $methods;
    }

    /**
     * @param $controllerName
     * @return object
     * @throws Exception
     */
    private function createObject($controllerName)
    {
        if (class_exists($controllerName)) {

            try {
                $reflectionObject = new \ReflectionClass($controllerName);

                if (is_null($parameters = $reflectionObject->getConstructor())) {
                    return new $controllerName;
                }

                $parameters = $reflectionObject->getConstructor()->getParameters();

                foreach ($parameters as $parameter) {
                    $dependencies[] = $parameter->getClass()->newInstance();
                }

                return $reflectionObject->newInstanceArgs($dependencies);

            } catch (ReflectionException $exception) {
                print_r($exception->getCode());
                echo "<br>";
                dd($exception->getMessage());
            }

        }

        throw new Exception("class $controllerName does not exist");
    }

    /**
     * @param $controllerName
     * @param $methodReadyToCall
     * @return array
     * @throws ReflectionException
     */
    private function findMethodArgs($controllerName, $methodReadyToCall)
    {

        try {
            $reflectionObject = new ReflectionMethod($controllerName, $methodReadyToCall);

            $methodParams = $reflectionObject->getParameters();

            $dependencies = [];
            foreach ($methodParams as $methodParam) {

                if (!is_object($methodParam->getClass())) {
                    $dependencies[] = $methodParam->getName();
                } else {
                        $dependencies[] = self::createDependency($methodParam);
                }

            }

            return [
                'reflectionObject' => $reflectionObject,
                'dependencies' => $dependencies
            ];
        } catch (ReflectionException $exception) {
            throw new ReflectionException($exception->getMessage());
        }

    }

    /**
     * @param $controllerObject
     * @param $methodParams
     */
    private function callMethod($controllerObject, $methodParams)
    {
        $methodParams['reflectionObject']->invokeArgs($controllerObject, $methodParams['dependencies']);
    }

    /**
     * @param $methodParam
     * @return mixed
     */
    private function createDependency($methodParam)
    {
        return $methodParam->getClass()->newInstance();
    }

    /**
     * @return string
     */
    private function ControllerNameSpace()
    {
        return 'App\Http\Controllers\\';
    }

    /**
     * @param $uri
     * @param $requestMethod
     * @return bool
     */
    protected function findURL($uri, $requestMethod)
    {
        if (array_key_exists($uri, $this->routes[$requestMethod])) {
            return true;
        } else {
            return false;
        }
    }


}