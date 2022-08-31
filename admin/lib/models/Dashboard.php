<?php

namespace Models;

class Dashboard extends Model
{
    /** Affiche les X derniers artistes ajoutés dans l'ordre descroissant ID
     * 
     * @return array
     */
    function lastArticlesAdd(): array
    {
        $sth = $this->dbh->prepare('SELECT * FROM artist ORDER BY art_id DESC LIMIT 3');
        $sth->execute();
        $items = $sth->fetchAll();
        return $items;
    }
    /** Affiche les X derniers chansons ajoutées dans l'ordre descroissant ID
     * 
     * @return array
     */
    function lastSongsAdd(): array
    {
        $sth = $this->dbh->prepare('SELECT * FROM song INNER JOIN artist ON (artist_art_id  = art_id)ORDER BY son_id DESC LIMIT 3');
        $sth->execute();
        $items = $sth->fetchAll();
        return $items;
    }
    /** Affiche les X derniers evenements ajoutés dans l'ordre descroissant ID
     * 
     * @return array
     */
    function lastEventsAdd(): array
    {
        $sth = $this->dbh->prepare('SELECT * FROM `event` ORDER BY eve_id DESC LIMIT 3');
        $sth->execute();
        $items = $sth->fetchAll();
        return $items;
    }
}
