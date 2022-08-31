<?php

namespace Models;

class Acceuil extends Model
{
    /** Cherche les 6 dernières chansons ajoutées
     * 
     * @return array
     */
    public function lastSongs()
    {
        $sth = $this->dbh->prepare('SELECT son_title, son_picture, son_description, son_song,art_id, art_lastname, art_firstname, art_pseudo 
                                    FROM song 
                                    INNER JOIN artist ON (art_id = artist_art_id)                                    
                                    ORDER BY son_id DESC 
                                    LIMIT 6');
        $sth->execute();

        $items = $sth->fetchAll();

        return $items;
    }

    /** Cherche les 6 dernières chansons ajoutées
     * 
     * @return array
     */
    public function showSongs()
    {
        $sth = $this->dbh->prepare('SELECT * 
                                    FROM song
                                    INNER JOIN artist ON (art_id = artist_art_id) 
                                    ORDER BY son_id DESC 
                                    LIMIT 15 OFFSET 6');
        $sth->execute();

        $items = $sth->fetchAll();

        return $items;
    }

    /** Cherche les 6 dernières chansons ajoutées
     * 
     * @return array
     */
    public function lastEvent()
    {
        $sth = $this->dbh->prepare('SELECT * 
                                    FROM event 
                                    ORDER BY eve_id DESC 
                                    LIMIT 1');
        $sth->execute();

        $item = $sth->fetch();

        return $item;
    }

    /** Cherche les 5 derniers evenements ajoutés
     * 
     * @return array
     */
    public function lastEvents()
    {
        $sth = $this->dbh->prepare('SELECT * 
                                    FROM event 
                                    ORDER BY eve_id DESC 
                                    LIMIT 4 OFFSET 1');
        $sth->execute();

        $items = $sth->fetchAll();

        return $items;
    }

    // /** Cherche les 6 dernières chansons ajoutées
    //  * 
    //  * @return array
    //  */
    // public function getCount()
    // {
    //     $sth = $this->dbh->prepare('SELECT COUNT(son_id) as nb
    //                     FROM song
    //                     ');
    //     $sth->execute();
    //     $nbSong = $sth->fetch();
    //     return $nbSong;
    // }
}
