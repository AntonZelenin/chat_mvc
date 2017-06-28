<?php

function __autoload($class_name)
{

    $array_paths = array(
        ROOT.'\\app\\models\\',
        ROOT.'\\app\\core\\',
        ROOT.'\\app\\controllers\\',
    );

    foreach ($array_paths as $path) {
        if (is_file($path)) {
            require_once ROOT.$path.$class_name.'.php';
        }
    }
}
