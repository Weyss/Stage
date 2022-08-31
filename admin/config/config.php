<?php

/*** CONSTANTE POUR LA BDD ***/
const DB_HOST = 'mysql:host=localhost;dbname=music;charset=UTF8';
const DB_USER = 'root';
const DB_PASS = '';

/*** CONSTANTE POUR L'UPLOAD ***/
/** PICTURE **/
const FILE_PIC = '../upload/pictures';
const PIC_ART = 'artist';
const PIC_SONG = 'song';
const PIC_EVENT = 'event';

/** SONG **/
const FILE_SONG = '../upload/songs';

/*** CONSTANTE POUR RECUPERER LES UPLOAD ***/
const PATH_PIC = 'upload/pictures';
const PATH_SONG = 'upload/songs';

/*** CONSTANTE POUR LES REDIRECTIONS ***/
const DASH_SHOW = "index.php?controller=dashboard&task=show";

const ART_SHOW = 'index.php?controller=artist&task=show';
const ART_EDIT_ID = 'index.php?controller=artist&task=edit&id=';
const ART_EDIT = 'index.php?controller=artist&task=edit';
const ART_DELETE = 'index.php?controller=artist&task=delete';

const EVE_SHOW = 'index.php?controller=event&task=show';
const EVE_EDIT = 'index.php?controller=event&task=edit';
const EVE_EDIT_ID = 'index.php?controller=event&task=edit&id=';
const EVE_DELETE = 'index.php?controller=event&task=delete';

const SONG_SHOW = 'index.php?controller=song&task=show';
const SONG_EDIT_ID = 'index.php?controller=song&task=edit&id=';
const SONG_EDIT = 'index.php?controller=song&task=edit';
const SONG_DELETE = 'index.php?controller=song&task=delete';
