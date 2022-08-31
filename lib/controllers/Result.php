<?php

namespace Controllers;

class Result extends Controller
{
    /** @var object Le nom du modèle */
    protected $modelName = \Models\Result::class;


    /** Affichage de la page d acceuil
     * 
     * @return void
     */

    public function show()
    {
        $mot = $_GET['result'];


        $artists = $this->model->artist($mot);
        $songs = $this->model->song($mot);

        $modelAcceuil = new \Models\Acceuil;
        $lastSongs = $modelAcceuil->lastSongs();
        $allSongs = $modelAcceuil->showSongs();
        $lastEvent = $modelAcceuil->lastEvent();
        $lastEvents = $modelAcceuil->lastEvents();

        require_once('view/resultView.phtml');
    }
}
