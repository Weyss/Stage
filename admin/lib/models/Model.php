<?php

namespace Models;

require_once('lib/bdd.lib.php');

abstract class Model
{
    /** @var PDO Connexion a la BDD */
    protected $dbh;
    /** @var string Nom de la table */
    protected $table;
    /** @var string Nom de la colonne ID situé dans la table */
    protected $colonneId;

    public function __construct()
    {
        $this->dbh = connect();
    }

    /** Récupere tous les artistes de la BDD
     * 
     * 
     * @return void
     */
    function findAll(): array
    {
        $sth = $this->dbh->prepare("SELECT * 
                                    FROM  {$this->table}
                                   ");
        $sth->execute();
        $items = $sth->fetchAll();
        return $items;
    }

    /** Retourne l'artiste en fonction de son id
     * 
     * @param int $id Le numéro de l'id de l'artiste
     * 
     * @return array Retourne l'artiste en fonction de son id
     */
    function findById($id)
    {
        $sth = $this->dbh->prepare("SELECT *
                        FROM {$this->table} 
                        WHERE {$this->colonneId} = :id");

        $sth->bindValue('id', $id);
        $sth->execute();
        $item = $sth->fetch();
        return $item;
    }
}
