<?php

class Picture extends Extension
{
    /**
     * @var array Tableau des extensions authorisées pour une image
     */
    protected $tableauExt = array('jpeg', 'png', 'gif');

    /** Retourne l'extention du fichier envoyer dans le formulaire
     * 
     * @return string
     */
    private static function extension(): string
    {
        $picture = new SplFileInfo($_FILES['picture']['name']);
        $picturExtension = $picture->getExtension();
        return $picturExtension;
    }

    /** Renvoie le nom de l'image avec un basename afin d'avoir plusieurs fois
     * la meme image
     * 
     *@param string $folder Le dossier dans lequel stocker l'image
     * 
     *@return string|null
     */
    public function create(string $folder)
    {
        if ($_FILES['picture']['name'] != '') {
            $pictureName =  uniqid() . basename($_FILES['picture']['name']);
            move_uploaded_file($_FILES['picture']['tmp_name'], FILE_PIC . '/' . $folder . '/' . $pictureName);
            return $pictureName;
        } else {
            $pictureName = null;
            return $pictureName;
        }
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
        if (array_key_exists('picture', $_FILES)) {
            if ($_FILES['picture']['name'] != '') {
                if (in_array(self::extension(), $this->authorizeExt)) {
                    $error = false;
                    return array('error' => $error);
                }
                // Si elle n'est pas conforme à au format désirée, on affiche un message d'erreur
                else {
                    $error = true;
                    $msgError = "L'extension de l'image n'est pas valide";
                    return array('error' => $error, 'msgError' => $msgError);
                }
            } else {
                $error = false;
                return array('error' => $error);
            }
        }
        return array();
    }

    /** Retourne la nouvelle image lors d'une édition
     * 
     * @param var $edit Variable dans laquelle on récuperer les données
     * @param string $folder Le dossier dans lequel est stocker l'image
     * 
     *@return string
     */
    public function edit($edit, $folder): string
    {
        if (array_key_exists('picture', $_FILES)) {
            if ($_FILES['picture']['name'] != '') {
                if ($edit != '' || $_FILES['picture']['name'] != $_POST['lastpicture'])
                    unlink(FILE_PIC . '/' . $folder . '/' . $_POST['lastpicture']);
                if (in_array(self::extension(), $this->authorizeExt)) {
                    $pictureName = $this->create($folder);
                    return $pictureName;
                }
            } else {
                $pictureName = $_POST['lastpicture'];
                return $pictureName;
            }
        }
    }
    public function convertImage($source, $dst, $width, $height, $quality)
    {

        $imageSize = getimagesize($source);
        $imageRessource = imagecreatefromjpeg($source);
        $imageFinal = imagecreatetruecolor($width, $height);
        imagecopyresampled($imageFinal, $imageRessource, 0, 0, 0, 0, $width, $height, $imageSize[0], $imageSize[1]);
        var_dump(imagejpeg($imageFinal, $dst, $quality));
    }
}
