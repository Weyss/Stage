<?php

namespace Controllers;

class Acceuil extends Controller
{
    /** @var object Le nom du modèle */
    protected $modelName = \Models\Acceuil::class;

    /** Permet l'affichage de l'Acceuil
     * 
     * @return void
     */
    public function show()
    {
        $lastSongs = $this->model->lastSongs();
        $allSongs = $this->model->showSongs();
        $lastEvent = $this->model->lastEvent();
        $lastEvents = $this->model->lastEvents();

        require_once('view/index.phtml');
    }

    /** Barre de recherche
     * 
     * @return array
     */
    public function search()
    {
        $modelSearch = new \Models\Search;

        $search = $_GET['search'];

        $byPseudo = $modelSearch->byArtistPseudo($search);
        $bySong = $modelSearch->bySongTitle($search);

        require_once('view/search.phtml');
    }
}
