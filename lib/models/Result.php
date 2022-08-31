<?php

namespace Models;

require_once('Model.php');

class Result extends Model
{
    /** Ajoute un artiste dans la BDD
     * 
     * @param string $pseudo Pseudo de l'artiste
     * 
     * @return array
     */
    public function artist($pseudo)
    {
        $sth = $this->dbh->prepare('SELECT *, son_title, son_id, son_song, son_picture
                                    FROM artist
                                    INNER JOIN song ON art_id = artist_art_id                                    
                                    WHERE art_pseudo = :pseudo
                                    ORDER BY son_id DESC');

        $sth->bindValue('pseudo', $pseudo);

        $sth->execute();

        $items = $sth->fetchAll();

        return $items;
    }
/** Ajoute un artiste dans la BDD
     * 
     * @param string $title Chanson
     * 
     * @return array
     */
    public function song($title)
    {
        $sth = $this->dbh->prepare('SELECT *, son_title, son_id, son_song, son_picture
                                    FROM artist
                                    INNER JOIN song ON art_id = artist_art_id                                    
                                    WHERE son_title = :title
                                    ORDER BY son_id DESC');

        $sth->bindValue('title', $title);

        $sth->execute();

        $items = $sth->fetchAll();

        return $items;
    }
}
