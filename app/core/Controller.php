<?php

class Controller {

    public function model($model){
        $file = ROOT.'\\app\\models' . $model . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            throw new Exception("Model file $file does not exist", 1);
        }

        return new $model();
    }

    public function view($view){
        $file = ROOT.'\\app\\views\\'.$view.'.php';

        if (file_exists($file)) {
            require $file;
        } else {
            throw new Exception("View file $file does not exist", 1);
        }
    }
}
