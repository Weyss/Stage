<?php

/**
 * Retourn la connexion à la BDD
 * 
 * @return PDO
 */
function connect(): PDO
{
    // Connextion a la base de donnée
    $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
    //On dit à PDO de nous envoyer une exception s'il n'arrive pas à se connecter ou s'il rencontre une erreur
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $dbh;
}
