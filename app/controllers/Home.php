<?php

class Home extends Controller
{
    public function index()
    {
        $is_authorized = false;

        if (!$is_authorized) {
            header("Location: ..\\public\\authorization");
        }

        require_once ROOT.'\\app\\views\\home.php';
    }
}
