<?php
spl_autoload_register(function ($className) {
    $className = str_replace("\\", "/", strtolower($className));
    require_once('lib/' . $className . '.php');
});
