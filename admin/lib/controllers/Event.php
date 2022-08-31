<?php

namespace Controllers;

class Event extends Controller implements Action
{
    /** @var object Le nom du modèle */
    protected $modelName = \Models\Event::class;

    /** Permet l'affichage des evenements
     * 
     * @return void
     */
    public function show()
    {
        session_start();
        $this->isConnected();

        $view = 'eventList';
        $title = 'Liste des Evenements';
        try {
            $artistModel = new \Models\Artist;
            $artists = $artistModel->findAll();
            $events = $this->model->findAll();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }

    /** Permet l'ajout d'evenement
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
            $errors = [];
            // Requête pour récuperer les artistes
            $artists =  $artistModel->findAll();
            if (array_key_exists('description', $_POST)) {
                // On récupère l'extension de l'image envoyée
                $picture = $pictureModel->validate();

                // On verifie que le titre ne soit pas vide
                if (trim($_POST['eventTitle']) == '')
                    $errors[] = 'Vous devez renseigner le Titre';

                // On vérifie que la description n'est pas vide.
                if (strip_tags(trim($_POST['description'])) == false)
                    $errors[] = 'Vous devez remplir la Description';

                // On verifie que l'image à la bonne extension
                if ($picture['error'] == true)
                    $errors[] = $picture['msgError'];

                // S'il y a des erreurs alors on passe pas de l'Ajax pour afficher les erreurs dans la modal
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
                    $pictureName = $pictureModel->create(PIC_EVENT);
                    //$pictureResize = $pictureModel->convertImage(FILE_PIC . '/' . PIC_EVENT . '/' . $pictureName, '../upload/pictures/carousel', '1140', '300', 100);
                    //var_dump($pictureResize);
                    $this->model->add($_POST['eventTitle'], $_POST['description'], $pictureName);
                    header('Location:' . DASH_SHOW);
                    exit();
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }


    /** Permet la suppression d'evenement
     * 
     * @return void
     */
    public function delete()
    {
        session_start();
        $this->isConnected();
        try {

            // On récupere les données de l'evenement ciblé par la suppression :
            if (array_key_exists('id', $_REQUEST)) {
                $event = $this->model->findById($_REQUEST['id']);
            }
            // Dans le cas quelqu'un voudrait supprimer un artist via son id directement dans l'URL
            // On sécurise :
            if ($event == false) {
                if ($this->isAjax()) {
                    http_response_code('400');
                    exit();
                }
                header('Location:' . EVE_SHOW);
                exit();
            }
            // On récuperer l'id de l'event existant pour le supprimer :
            if (array_key_exists('id', $_POST)) {
                $this->model->delete($_POST['id'], $event['eve_picture']);
                header('Location:' . EVE_SHOW);
                exit();
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /** Permet l'édition d'evenement
     * 
     * @return void
     */
    public function edit()
    {
        session_start();
        $this->isConnected();

        $view = 'eventEdit';
        $title = 'Editer un évènement';

        try {
            $pictureModel = new \Picture;
            $errors = [];
            // On récuperer les données de l'évènement à éditer
            // Requete pour récuperer les artists et les afficher dans la selection
            if (array_key_exists('id', $_GET)) {
                $event = $this->model->findById($_GET['id']);
                $artistsConfirmed = $this->model->artistsConfirmed($_GET['id']);
                $artistsAvailable = $this->model->artistsAvailable();
                // On protège les données au cas où quelqu'un voudrait modifier la chanson via son id
                if ($event == false) {
                    header('Location:' . EVE_SHOW);
                    exit();
                } else {
                    $this->getData();
                    // On insere les nouvelles données à l'index data
                    if (!isset($_SESSION['data']) || !is_array($_SESSION['data'])) {
                        $_SESSION['data'] = array();
                        $_SESSION['data'] = [
                            'id' => $event['eve_id'],
                            'title' => $event['eve_title'],
                            'description' => $event['eve_description'],
                            'picture' => $event['eve_picture'],
                            'artistAvailable' => $artistsAvailable,
                            'artistConfirmed' => $artistsConfirmed
                        ];
                    }
                }
            }
            //var_dump($_SESSION['data']);
            // Requête pour mettre à jour la chanson
            if (array_key_exists('id', $_POST)) {
                // On récupère l'extension de l'image envoyée  
                $picture = $pictureModel->validate();

                if ($picture['error'] == true)
                    $errors[] = $picture['msgError'];

                // On vérifie que la description n'est pas vide.
                if (strip_tags(trim($_POST['description'])) == false)
                    $errors[] = 'Vous devez remplir la Description';

                // var_dump($errors);
                // exit;
                if (empty($errors)) {
                    $picture = $pictureModel->edit($_SESSION['data']['picture'], PIC_EVENT);
                    var_dump($picture);
                    $this->model->edit($_POST['id'], $_POST['eventTitle'], $_POST['description'], $picture, $_POST['artist']);
                    unset($_SESSION['data']);
                    header('Location:' . EVE_SHOW);
                    exit();
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/layout.phtml');
    }

    /** Permet la suppression d'un artiste dans un evenement
     * 
     * @return void
     */
    function remove()
    {
        session_start();
        $this->isConnected();
        try {
            $artistModel = new \Models\Artist;
            // On récupere les données de l'evenement ciblé par la suppression :
            if (array_key_exists('id', $_REQUEST)) {
                $artist = $artistModel->findById($_REQUEST['id']);
            }
            $event = $this->model->eventByArtist($artist['art_id']);
            // On récuperer l'id de l'event existant pour le supprimer :
            if (array_key_exists('id', $_POST)) {
                $this->model->removeArtist($_POST['id']);
                header('Location:' . EVE_EDIT . $event['eve_id']);
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}
