<?php

namespace Controllers;

class Song extends Controller implements Action
{
    /** @var object Le nom du modèle */
    protected $modelName = \Models\Song::class;

    /** Permet d'afficher la liste des chansons
     * 
     * @return void
     */
    public function show()
    {
        session_start();
        $this->isConnected();

        $view = 'songList';
        $title = 'Liste des chansons';

        try {
            $artistModel = new \Models\Artist;
            $artists = $artistModel->findAll();
            $songs = $this->model->findAll();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }

    /** Permet d'ajouter des chansons
     * 
     * @return void
     */
    public function add()
    {
        session_start();
        $this->isConnected();

        try {
            $artistModel = new \Models\Artist;
            $pictureModel = new \Picture;
            $songModel = new \Song;
            $errors = [];
            // Requête pour avoir les artistes
            $artists = $artistModel->findAll();
            if (array_key_exists('songTitle', $_POST)) {
                // On récupère l'extension de l'image envoyée   
                $picture = $pictureModel->validate();
                $song = $songModel->validate();

                // On verifie que le titre ne soit pas vide
                if (trim($_POST['songTitle']) == '')
                    $errors[] = 'Vous devez renseigner le Titre';

                // On verifie qu'un fichier à bien été mis
                if ($_FILES['song']['size'] == 0) {
                    $errors[] = "Vous devez ajouter une Musique";
                }

                // On verifie que la musique à la bonne extension
                if ($_FILES['song']['size'] > 0 && $song['error'] == true)
                    $errors[] = $song['msgError'];

                // On verifie que l'image à la bonne extension
                if ($picture['error'] == true)
                    $errors[] = $picture['msgError'];

                if (!empty($errors)) {
                    if ($this->isAjax()) {
                        echo json_encode($errors);
                        header('Content-Type: application/json');
                        http_response_code('400');
                        exit();
                    }
                } else {
                    $pictureName = $pictureModel->create(PIC_SONG);
                    $songName = $songModel->create();
                    $this->model->add($_POST['songTitle'], $_POST['description'], $pictureName, $_POST['artist'], $songName);
                    header('Location:' . DASH_SHOW);
                    exit();
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }

    /** Permet la suppression des chansons
     *   
     * @return void
     */
    public function delete()
    {
        session_start();
        $this->isConnected();
        try {
            // On récuperer la chansons ciblé pour la suppression
            if (array_key_exists('id', $_REQUEST)) {
                $song = $this->model->findById($_REQUEST['id']);
            }
            // Dans le cas quelqu'un voudrait supprimer une chanson via son id directement dans l'URL
            // On sécurise :
            if ($song == false) {
                if ($this->isAjax()) {
                    http_response_code('400');
                    exit();
                }
                header('Location:' . SONG_SHOW);
                exit();
            }
            // On récuperer l'id de la chanson existante pour la supprimer :
            if (array_key_exists('id', $_POST)) {
                $this->model->delete($_POST['id'], $song['son_picture'], $song['son_song']);
                header('Location:' . SONG_SHOW);
                exit();
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /** Permet l'édition des chansons
     * 
     * @return void
     */
    public function edit()
    {
        session_start();
        $this->isConnected();

        $view = 'songEdit';
        $title = 'Editer une chanson';

        try {
            $artistModel = new \Models\Artist;
            $pictureModel = new \Picture;
            $picture['error'] = false;
            // On récuperer les données de la chanson à éditer
            // Requete pour récuperer les artists et les afficher dans la selection
            $artists = $artistModel->findAll();
            if (array_key_exists('id', $_GET)) {
                $song = $this->model->findById($_GET['id']);
                // On protège les données au cas où quelqu'un voudrait modifier la chanson via son id
                if ($song == false) {
                    header('Location:' . SONG_SHOW);
                    exit();
                } else {
                    $this->getData();
                    // On insere les nouvelles données à l'index data
                    if (!isset($_SESSION['data']) || !is_array($_SESSION['data'])) {
                        $_SESSION['data'] = array();
                        $_SESSION['data'] = [
                            'id' => $song['son_id'],
                            'title' => $song['son_title'],
                            'description' => $song['son_description'],
                            'artist' => $song['artist_art_id'],
                            'picture' => $song['son_picture']
                        ];
                    }
                }
            }
            // Requête pour mettre à jour la chanson
            if (array_key_exists('songTitle', $_POST)) {
                // On récupère l'extension de l'image envoyée   
                $picture = $pictureModel->validate();
                if ($picture['error'] == false) {
                    $picture = $pictureModel->edit($_SESSION['data']['picture'], PIC_SONG);
                    $this->model->edit($_POST['id'], $_POST['songTitle'], $_POST['description'], $picture, $_POST['artist']);
                    header('Location:' . SONG_SHOW);
                    exit();
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }
}
