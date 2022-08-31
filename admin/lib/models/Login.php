<?php

namespace Models;

class Login extends Model
{
    /** Récuperer le mot de passe
     * 
     * @return array
     */
    function recuPass()
    {
        $sth = $this->dbh->prepare('SELECT * FROM manager');
        $sth->execute();
        $item = $sth->fetch();
        // var_dump($item);
        // exit;
        if ($item == false)
            return $item;
        else
            return $item;
    }

    function createPass($passReceived): void
    {
        $sth = $this->dbh->prepare('INSERT INTO manager (man_password) 
                                    VALUES (:password)');
        $password = password_hash($passReceived, PASSWORD_DEFAULT);
        $sth->bindValue('password', $password);

        $sth->execute();
    }
}
