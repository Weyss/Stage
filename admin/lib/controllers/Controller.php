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

    /** Permet de vérifier si on est bien connecté
     * 
     * @return void
     */
    public function isConnected()
    {
        if ($_SESSION['connect'] !== true) {
            header('Location:index.php?controller=login&task=connexion');
            exit();
        }
    }
    /** Permet de vérifier si on est bien en AJAX
     * 
     * @return bool
     */
    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function getData()
    {
        // S'il y a des données dans la session a l'index data on les effaces
        if (isset($_SESSION['data']) && is_array($_SESSION['data']))
            unset($_SESSION['data']);
    }
}
