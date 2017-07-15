<?php

define('ROOT', __DIR__.'\\..\\..');
require_once(ROOT.'\\app\\core\\Autoload.php');


$echo = new ChatWebSocketServer("0.0.0.0","9000");

try {
    $echo->run();
}
catch (Exception $e) {
    $echo->stdout($e->getMessage());
}
