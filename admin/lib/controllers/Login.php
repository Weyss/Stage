<?php

namespace Controllers;

class Login extends Controller
{
    protected $modelName = \Models\Login::class;

    /** Permet de se connecter et commencer la session d'admin  
     * 
     * @return void
     */
    function connexion()
    {
        session_start();
        $hasLog = true;
        try {
            // On récuperer le mot de passe
            $login = $this->model->recuPass();
            if ($login == false) {
                $hasLog = false;
            } elseif (array_key_exists('password', $_POST)) {
                // On verifie que le mot de passe entrer correspond a celui de la BDD
                $verify = password_verify($_POST['password'], $login['man_password']);
                //  Si c'est ok alors la session commence
                if ($verify === true) {
                    $_SESSION['connect'] = true;
                    header('Location:index.php?controller=dashboard&task=show');
                    exit();
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/login.phtml');
    }

    /** Permet la création d'un mot de passe  
     * 
     * @return void
     */
    function create()
    {
        session_start();
        try {
            $error = '';
            if (array_key_exists('password', $_POST)) {
                if ($_POST['password'] !== $_POST['passconfirmed'])
                    $error = 'Vos mots de passe ne correspondent pas';

                if ($error != '') {
                    if ($this->isAjax()) {
                        echo json_encode($error);
                        header('Content-Type: application/json');
                        http_response_code('400');
                        exit();
                    }
                } elseif ($error == '') {
                    $this->model->createPass($_POST['password']);
                    header('Location:index.php?controller=login&task=connexion');
                }
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        require('tpl/login.phtml');
    }
}
