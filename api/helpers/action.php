<?php
namespace EntermotionTrial\Helpers;

class Action
{
    protected $controller,$action;
    function __construct($controller,$action)
    {
        $controllerClass = "\\EntermotionTrial\\Controllers\\".ucfirst($controller);

        if (class_exists($controllerClass )) {
            $this->controller = new $controllerClass;
            if (method_exists($this->controller,$action)) {
                $this->action = $action;
            } else {
                throw new \Exception("Controller $controllerClass does not have a $action method.", 1);
            }
        } else {
          throw new \Exception("Controller $controllerClass does not exist.", 1);
        }
    }

    function exec()
    {
        return $this->controller->{$this->action}();
    }
}
