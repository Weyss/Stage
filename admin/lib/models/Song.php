<?php

namespace Models;

class Song extends Model
{
    /** @var string La table dans laquelle faire nos requête */
    protected $table = "song";

    /** @var string La colonne ID correspondant à la table */
    protected $colonneId = "son_id";

    /** Récupere la liste des chansons
     * 
     * @return array
     */
    function findAll(): array
    {
        // Requete qui récuprer toutes les chansons de la base de données.
        $sth = $this->dbh->prepare('SELECT *  
                                    FROM song 
                                    INNER JOIN artist ON (artist_art_id = art_id)');
        $sth->execute();
        $songs = $sth->fetchAll();

        return $songs;
    }

    /** Ajout une chanson de la BDD
     * 
     * @param string $title Le titre de la chanson
     * @param string $description La desscription de la chanson
     * @param string $picture L'image de la chanson
     * @param int $artist L'id de l'artiste
     * 
     * @return void
     */
    function add($title, $description, $picture, $artist, $song): void
    {
        $sth = $this->dbh->prepare('INSERT INTO song (son_title, son_picture, son_description, son_song, artist_art_id) 
                                    VALUE (:title, :picture, :description, :song, :artist_id)');

        $sth->bindValue('title', $title);
        $sth->bindValue('picture', $picture);
        $sth->bindValue('description', $description);
        $sth->bindValue('song', $song);
        $sth->bindValue('artist_id', $artist);
        $sth->execute();
    }

    /** Récupere une chanson par rapport a son ID
     * 
     * @param int $id L'id de la chanson
     * 
     * @return array
     */
    function findByArtist($id): array
    {

        $sth = $this->dbh->prepare('SELECT *
                                    FROM song 
                                    WHERE artist_art_id = :id');

        $sth->bindValue('id', $id);
        $sth->execute();
        $songs = $sth->fetchAll();

        return $songs;
    }

    /** Supprime une chanson par rapport a son ID
     * 
     * @param int $id L'id de la chanson
     * @param string $picture L'image de la chanson
     * 
     * @return void
     */
    function delete($id, $picture, $song): void
    {

        $sth = $this->dbh->prepare('DELETE FROM song 
                                    WHERE son_id = :id');
        $sth->bindValue('id', $id);
        $sth->execute();

        if ($picture != null)
            unlink(FILE_PIC . '/' . PIC_SONG . '/' . $picture);

        if ($song != null)
            unlink(FILE_SONG . '/' . $song);
    }

    /** Edit une chanson par rapport a son ID
     *  
     * @param int $id L'id de la chanson
     * @param string $title Le titre de la chanson
     * @param string $description La desscription de la chanson
     * @param string $picture L'image de la chanson
     * @param int $artist L'id de l'artiste
     * 
     * @return void
     */
    function edit($id, $title, $description, $picture, $artist): void
    {

        $sth = $this->dbh->prepare('UPDATE song
                                SET son_title = :title, son_description=:description, son_picture=:picture, artist_art_id=:artistid
                                WHERE son_id = :id');

        $sth->bindValue('id', $id);
        $sth->bindValue('title', $title);
        $sth->bindValue('description', $description);
        $sth->bindValue('picture', $picture);
        $sth->bindValue('artistid', $artist);

        $sth->execute();
    }
}
