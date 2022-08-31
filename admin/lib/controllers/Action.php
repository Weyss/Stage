<?php

namespace Controllers;

interface Action
{
    /**
     * Affiche une liste
     */
    public function show();
    /**
     * Affiche la page d'ajout
     */
    public function add();
    /**
     * Appel à la suppression
     */
    public function delete();
    /**
     * Affiche la page d'édition
     */
    public function edit();
}
