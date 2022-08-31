<?php

class Song extends Extension
{
    /**
     * @var array Tableau des extensions authorisées pour une image
     */
    protected $tableauExt = array('mp3', 'mp4');

    /** Retourne l'extention du fichier envoyer dans le formulaire
     * 
     * @return string
     */
    private static function extension(): string
    {
        $song = new SplFileInfo($_FILES['song']['name']);
        $songExtension = $song->getExtension();
        return $songExtension;
    }

    /** Renvoie le nom de l'image avec un basename afin d'avoir plusieurs fois
     * la meme image
     * 
     *@param string $folder Le dossier dans lequel stocker l'image
     * 
     *@return string
     */
    public function create(): string
    {
        $songName =  uniqid() . basename($_FILES['song']['name']);
        move_uploaded_file($_FILES['song']['tmp_name'], FILE_SONG . '/' . $songName);
        return $songName;
    }

    /** Retourne l'image en cas de succès sinon une erreur
     * 
     * @param string $folder Le dossier dans lequel sera stocker l'image
     * @param array $authorizeExt Tableau des extensions authorisées
     * 
     * @return array
     */
    public function validate(): array
    {
        if (array_key_exists('song', $_FILES)) {
            if ($_FILES['song']['name'] != '') {
                if (in_array(self::extension(), $this->authorizeExt)) {
                    $error = false;
                    return array('error' => $error);
                } // Si elle n'est pas conforme à au format désirée, on affiche un message d'erreur
                else {
                    $error = true;
                    $msgError = "L'extension de la chanson n'est pas valide";
                    return array('error' => $error, 'msgError' => $msgError);
                }
            }
        }
        return array();
    }
}
