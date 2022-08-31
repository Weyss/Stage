<?php

namespace Models;

require('admin/config/config.php');
require('admin/lib/bdd.lib.php');

abstract class Model
{
    /** @var PDO Connexion a la BDD */
    protected $dbh;

    public function __construct()
    {
        $this->dbh = connect();
    }
}
