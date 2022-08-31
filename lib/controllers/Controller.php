<?php

namespace Controllers;

abstract class Controller
{
    /** @var object Le modèle utilisé*/
    protected $model;

    /** @var object Le nom du modèle */
    protected $modelName;

    public function __construct()
    {
        $this->model = new $this->modelName;
    }
}
