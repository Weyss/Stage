<?php

abstract class Extension
{
    /**
     * @var array Tableau des extensions authorisées
     */
    protected $authorizeExt;
    /**
     * @var array Tableau des extensions
     */
    protected $tableauExt;

    public function __construct()
    {
        $this->authorizeExt = $this->tableauExt;
    }
}
