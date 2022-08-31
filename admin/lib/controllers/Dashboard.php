<?php

namespace Controllers;

class DashBoard extends Controller
{
    /** @var object Le nom du modèle */
    protected $modelName = \Models\Dashboard::class;

    /** Permet l'affichage du Dashboard
     * 
     * @return void
     */
    function show()
    {
        session_start();

        $this->isConnected();
        $view = 'index';
        $title = 'DashBoard';
        try {
            $artists = $this->model->lastArticlesAdd();
            $songs = $this->model->lastSongsAdd();
            $events = $this->model->lastEventsAdd();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }
}
