<?php


$errors = [];
$view = 'index';

function isAjax()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (array_key_exists('message', $_POST)) {

    if (trim($_POST['lastName']) == '')
        $errors[] = "Le champs suivant ne peut être vide : Nom";

    if (trim($_POST['firstName']) == '')
        $errors[] = "Le champs suivant ne peut être vide : Prénom";

    if (trim($_POST['mail']) == '') {
        $errors[] = "Le champ suivant ne peut être vide : Mail";
        if (filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) == false)
            $errors[] = "Format d'email invalide";
    }
    if (trim($_POST['sujet']) == '')
        $errors[] = "Donnez nous le sujet de votre message";

    if (trim($_POST['message']) == '')
        $errors[] = "Vous ne pouvez envoyer un message vide";

    if (!empty($errors)) {
        if (isAjax()) {
            echo json_encode($errors);
            header('Content-Type: application/json');
            http_response_code('400');
            exit();
        }
    } else {
        mail('nakzorh@gmail.com', $_POST['sujet'], $_POST['message'], $_POST['mail']);
    }
}
