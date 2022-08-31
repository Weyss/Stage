<?php

class Application
{
    /** Permet de définir quel coontroller est appelé et se tâche associé
     * 
     * @return void
     */
    public static function process()
    {
        $controllerName = "Acceuil";
        $task = "show";

        if (!empty($_GET['controller']))
            $controllerName = ucfirst($_GET['controller']);

        if (!empty($_GET['task']))
            $task = strtolower($_GET['task']);

        $controllerName = "\Controllers\\" . $controllerName;

        $controller = new $controllerName;
        $controller->$task();
    }
}
