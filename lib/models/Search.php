<?php

namespace Models;

require_once('Model.php');

class Search extends Model
{
    /** Recherche un artist
     * 
     * @param string $pseudo Le pseudo de l artiste recherché
     * 
     * @return array
     */
    public function byArtistPseudo($pseudo)
    {
        $sth = $this->dbh->prepare('SELECT art_lastname, art_firstname, art_pseudo
                                    FROM artist                                    
                                    WHERE art_pseudo LIKE :pseudo');

        $sth->bindValue('pseudo', ucfirst($pseudo) . '%');

        $sth->execute();

        $items = $sth->fetchAll();

        return $items;
    }

    /** Recherche une chanson
     * 
     * @param string $title La chanson recherchée
     * 
     * @return array
     */
    public function bySongTitle($title)
    {
        $sth = $this->dbh->prepare('SELECT son_title
                                    FROM song                                   
                                    WHERE son_title LIKE :pseudo');

        $sth->bindValue('pseudo', ucfirst($title) . '%');

        $sth->execute();

        $items = $sth->fetchAll();

        return $items;
    }
}
