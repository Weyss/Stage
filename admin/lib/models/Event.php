<?php

namespace Models;

class Event extends Model
{
    /** @var string La table dans laquelle faire nos requête */
    protected $table = "event";

    /** @var string La colonne ID correspondant à la table */
    protected $colonneId = "eve_id";

    /** Ajout un évènement 
     *  
     * @param string $title Le titre de l'évènement
     * @param string $description La desscription de l'évènement
     * @param string $picture L'image de l'évènement
     * 
     * @return void
     */
    function add($title, $description, $picture): void
    {
        $sth = $this->dbh->prepare('INSERT INTO `event`(eve_title, eve_picture, eve_description) 
                                VALUES (:title, :picture, :description)');
        $sth->bindValue('title', $title);
        $sth->bindValue('picture', $picture);
        $sth->bindValue('description', $description);
        $sth->execute();
        $eventArtists = $this->dbh->lastInsertId();

        // Cas particulier : ce foreach sert a inserer dans notre table $artists
        //le faite qu'on puisse avoir plusieurs artists pour un event
        foreach ($_POST['artist'] as $artist) {
            $sth1 = $this->dbh->prepare('INSERT INTO artist_has_event(artist_art_id, event_eve_id) 
                                        VALUES (:artist, :event)');
            $sth1->bindValue('artist', $artist);
            $sth1->bindValue('event', $eventArtists);
            $sth1->execute();
        }
    }

    /** Supprime une event par rapport a son ID
     * 
     * @param int $id L'id de l'event
     * @param string $picture L'image de l'évènement
     * 
     * @return void
     */
    function delete($id, $picture): void
    {
        $sth = $this->dbh->prepare('DELETE 
                                     FROM artist_has_event
                                    WHERE event_eve_id = :id
                                    ');
        $sth->bindValue('id', $id);
        $sth->execute();

        $sth1 = $this->dbh->prepare('DELETE
                                    FROM `event`
                                    WHERE eve_id = :id
                                    ');
        $sth1->bindValue('id', $id);
        $sth1->execute();

        unlink(FILE_PIC . '/' . PIC_EVENT . '/' . $picture);
    }

    /** Editer évènement par rapport a son ID
     *   
     * @param int $id L'id de l'event
     * @param string $title Le titre de l'évènement
     * @param string $description La desscription de l'évènement
     * @param string $picture L'image de l'évènement
     * 
     * @return void
     */
    function edit($id, $title, $description, $picture, $artists): void
    {
        $sth = $this->dbh->prepare('UPDATE `event`
                                     SET eve_title = :title, eve_description=:description, eve_picture=:picture
                                    WHERE eve_id = :id');

        $sth->bindValue('id', $id);
        $sth->bindValue('title', $title);
        $sth->bindValue('description', $description);
        $sth->bindValue('picture', $picture);
        $sth->execute();

        // Pour les artistes lié a l'event, on est obligé de supprimer la table class_has_article
        // à l'id de l'event ciblé
        // Requête de suppression des artistes
        $sth1 = $this->dbh->prepare('DELETE 
                                    FROM artist_has_event
                                    WHERE artist_art_id = :id
                                    ');

        $sth1->bindValue('id', $id);
        $sth1->execute();

        // Requête d'insertion des artistes
        foreach ($artists as $artist) {
            $sth2 = $this->dbh->prepare('INSERT INTO artist_has_event (artist_art_id, event_eve_id) 
                                        VALUES (:artist, :event)');

            $sth2->bindValue('artist', $artist);
            $sth2->bindValue('event', $id);
            $sth2->execute();
        }
    }

    /** Recupere les artistes confirmés pour l'event
     *   
     * @param int $id L'id de l'evenement
     * 
     * @return array
     */
    function artistsConfirmed($id): array
    {
        $sth = $this->dbh->prepare('SELECT art_id, art_lastname, art_firstname, art_pseudo
                                    FROM artist_has_event INNER JOIN artist ON ( artist_art_id = art_id )
                                    WHERE event_eve_id = :id');
        $sth->bindValue('id', $id);
        $sth->execute();
        $items = $sth->fetchAll();

        return $items;
    }

    /** Recupere les artistes disponible pour l'event
     *   
     * @param int $id L'id de l'evenement
     * 
     * @return array
     */
    function artistsAvailable(): array
    {
        $sth = $this->dbh->prepare('SELECT * FROM artist
                                    LEFT JOIN artist_has_event ON artist_art_id = art_id
                                    WHERE event_eve_id IS NULL');
        $sth->execute();
        $items = $sth->fetchAll();
        return $items;
    }

    /** Editer évènement par rapport a son ID
     *   
     * @param int $id L'id de l'artiste
     * 
     * @return void
     */
    function removeArtist($id): void
    {
        $sth = $this->dbh->prepare('DELETE FROM artist_has_event
                                    WHERE artist_art_id = :id');

        $sth->bindValue('id', $id);
        $sth->execute();
    }

    /** Editer évènement par rapport a son ID
     *   
     * @param int $id L'id de l'artiste
     * 
     * @return array
     */
    function eventByArtist($id): array
    {
        $sth = $this->dbh->prepare('SELECT eve_id 
                                    FROM `event` 
                                    INNER JOIN artist_has_event 
                                    ON eve_id = event_eve_id 
                                    WHERE artist_art_id = :id');

        $sth->bindValue('id', $id);
        $sth->execute();
        $item = $sth->fetch();

        return $item;
    }
}
