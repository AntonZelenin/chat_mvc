<?php

function __autoload($class_name)
{

    $array_paths = array(
        ROOT.'\\app\\models\\',
        ROOT.'\\app\\core\\',
        ROOT.'\\app\\controllers\\',
    );

    foreach ($array_paths as $path) {
        if (is_file($path.$class_name.'.php')) {
            require_once $path.$class_name.'.php';
        }
    }
}
