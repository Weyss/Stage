<?php

namespace Controllers;

class Logout
{
    /** Permet de mettre fin à la session admin
     * 
     * @return void
     */
    public function deconnexion()
    {
        session_start();
        if ($_SESSION['connect'] === true) {
            // Quand on clique sur le bouton LogOut, on termine la session
            $_SESSION['connect'] = false;
            unset($_SESSION['connect']);
            header('Location:index.php?controller=login&task=connexion');
            exit();
        }
    }
}
