<?php
namespace DropboxBridge\Helpers;

class Router
{
    protected $routes;
    function __construct($routesFile)
    {
        if (file_exists($routesFile)) {

            $decodedRoutes = json_decode(file_get_contents($routesFile),true);
            if ($decodedRoutes) {
              $this->routes = $decodedRoutes;
            } else {
              throw new \Exception("Router constructor expects a json file. \"$routesFile\" given.", 1);
            }
        } else {
          throw new \Exception("Router constructor expects a filepath. \"$routesFile\" given.", 1);
        }
    }
    function getAction($url)
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        if (array_key_exists($url,$this->routes)) {
            $route = $this->routes[$url];
            if (array_key_exists($method,$route)) {
               return new Action($route[$method]['controller'],$route[$method]['action']);
            }
        }
        return false;
    }
}
