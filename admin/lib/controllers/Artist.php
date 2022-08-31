<?php

namespace Controllers;

class Artist extends Controller implements Action
{
    /** @var object Le nom du modèle */
    protected $modelName = \Models\Artist::class;

    /** Permet l'affichage des artistes
     * 
     * @return void
     */
    public function show()
    {
        session_start();
        $this->isConnected();

        $view = 'artistList';
        $title = 'Liste des Artistes';

        try {
            $artists = $this->model->findAll();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }

    /** Permet l'ajout d'artistes
     * 
     * @return void
     */
    public function add()
    {
        session_start();
        $this->isConnected();

        try {
            $pictureModel = new \Picture;
            $errors = [];
            if (array_key_exists('lastName', $_POST)) {
                // Ajout de l'image dans le dossier Upload
                $picture = $pictureModel->validate();

                // On verifie que le nom ne soit pas vide
                if (trim($_POST['lastName']) == '')
                    $errors[] = 'Vous devez renseigner le Nom';

                // On verifie que le prénom ne soit pas vide
                if (trim($_POST['firstName']) == '')
                    $errors[] = 'Vous devez renseigner le Prénom';

                // On verifie que l'image à la bonne extension
                if ($picture['error'] == true)
                    $errors[] = $picture['msgError'];

                // S'il y a des erreurs alors on passe par de l'Ajax pour afficher les erreurs dans la modal
                if (!empty($errors)) {
                    if ($this->isAjax()) {
                        echo json_encode($errors);
                        header('Content-Type: application/json');
                        http_response_code('400');
                        exit();
                    }
                }
                // Si aucunes erreurs, on ajoute toutes les données dans la BDD
                else {
                    $pictureName = $pictureModel->create(PIC_ART);
                    $this->model->add($_POST['lastName'], $_POST['firstName'], $_POST['pseudo'], $pictureName);
                    header('Location: ' . DASH_SHOW);
                    exit();
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }

    /** Permet la suppression d'artiste
     * 
     * @return void
     */
    public function delete()
    {
        session_start();
        $this->isConnected();

        try {
            $songModel = new \Models\Song;
            // On récupere les données de l'artiste ainsi que ces musiques ciblé par la suppression :
            if (array_key_exists('id', $_REQUEST)) {
                $artist = $this->model->findById($_REQUEST['id']);
                $songs = $songModel->findByArtist($artist['art_id']);
            }
            // Dans le cas quelqu'un voudrait supprimer un artist via son id directement dans l'URL
            // On sécurise :

            if ($artist == false) {
                if ($this->isAjax()) {
                    http_response_code('400');
                    exit();
                }
                header('Location:' . ART_SHOW);
                exit();
            }
            // On supprime l'artiste ainsi que ces musiques :
            if (array_key_exists('id', $_POST)) {
                foreach ($songs as $song) {
                    $songModel->delete($song['son_id'], $song['son_picture'], $song['son_song']);
                }
                $this->model->delete($_POST['id'], $artist['art_picture']);
                header('Location:' . ART_SHOW);
                exit();
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /** Permet l'edition d'artistes
     * 
     * @return void
     */
    public function edit()
    {
        session_start();
        $this->isConnected();

        $view = 'artistEdit';
        $title = 'Editer un artiste';

        try {
            $pictureModel = new \Picture;
            $picture['error'] = false;
            // On récuperer les données de l'artist à éditer
            if (array_key_exists('id', $_GET)) {
                $artist = $this->model->findById($_GET['id']);
                if ($artist == false) {
                    header('Location:' . ART_SHOW);
                    exit();
                } else {
                    $this->getData();
                    // On insere les nouvelles données à l'index data
                    if (!isset($_SESSION['data']) || !is_array($_SESSION['data'])) {
                        $_SESSION['data'] = array();
                        $_SESSION['data'] = [
                            'id' => $artist['art_id'],
                            'lastname' => $artist['art_lastname'],
                            'firstname' => $artist['art_firstname'],
                            'nickname' => $artist['art_pseudo'],
                            'picture' => $artist['art_picture']
                        ];
                    }
                }
            }
            if (array_key_exists('id', $_POST)) {
                // Modification de l'image
                $picture = $pictureModel->validate();
                if ($picture['error'] == false) {
                    $picture = $pictureModel->edit($_SESSION['data']['picture'], PIC_ART);
                    $this->model->edit($_POST['id'], $_POST['lastName'], $_POST['firstName'], $_POST['pseudo'], $picture);
                    header('Location:' . ART_SHOW);
                    exit();
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }
}
