<?php

namespace Models;

class Artist extends Model
{
    /** @var string La table dans laquelle faire nos requête */
    protected $table = "artist";

    /** @var string La colonne ID correspondant à la table */
    protected $colonneId = "art_id";

    /** Ajoute un artiste dans la BDD
     * 
     * @param string $lastname Nom de l'artiste
     * @param string $firstname Prénom de l'artiste
     * @param string $pseudo Pseudo de l'artiste
     * @param string $picture L'image de l'artiste
     * 
     * @return void
     */
    function add($lastname, $firstname, $pseudo, $picture): void
    {
        $sth = $this->dbh->prepare('INSERT INTO artist (art_lastname, art_firstname, art_pseudo, art_picture) 
                                VALUES (:lastname, :firstname, :pseudo, :picture)');

        $sth->bindValue('lastname', $lastname);
        $sth->bindValue('firstname', $firstname);
        $sth->bindValue('pseudo', $pseudo);
        $sth->bindValue('picture', $picture);
        $sth->execute();
    }

    /** Supprimer un artist en fonction de son ID
     * 
     * @param int $id L'id de l'artiste
     * @param string $picture L'image de l'artiste
     * 
     * @return void
     */
    function delete($id, $picture): void
    {
        $sth = $this->dbh->prepare('DELETE 
                        FROM artist_has_event
                        WHERE artist_art_id = :id
                        ');
        $sth->bindValue('id', $id);
        $sth->execute();
        $sth1 = $this->dbh->prepare('DELETE FROM artist 
                            WHERE art_id = :id');
        $sth1->bindValue('id', $id);
        $sth1->execute();

        if ($picture != null)
            unlink(FILE_PIC . '/' . PIC_ART . '/' . $picture);
    }

    /** Edite un artist en fonction de son ID
     * 
     * @param int $id L'id de l'artiste
     * @param string $lastname Nom de l'artiste
     * @param string Prénom de l'artiste
     * @param string Pseudo de l'artiste
     * @param string L'image de l'artiste
     * 
     * @return void 
     */
    function edit($id, $lastname, $firstname, $pseudo, $picture): void
    {
        $sth = $this->dbh->prepare('UPDATE artist SET art_lastname=:lastname,art_firstname=:firstname,art_picture=:picture,art_pseudo=:pseudo  
                        WHERE art_id = :id');
        $sth->bindValue('id', $id);
        $sth->bindValue('lastname', $lastname);
        $sth->bindValue('firstname', $firstname);
        $sth->bindValue('picture', $picture);
        $sth->bindValue('pseudo', $pseudo);
        $sth->execute();
    }
}
