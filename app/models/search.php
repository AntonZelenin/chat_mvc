<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/classes/auth.php';
//require_once 'C:\wamp64\www\chat_php_mysql_jquery_socket\classes\auth.php';

function make_user($name, $img, $message){
    $user = "<div class='user' onclick='choose_dialog()'>
                <img src='$img' class='img-circle'/>
                <div class='user-name'> <b>$name</b> </div>
                <div class='message-short'>$message</div>
            </div>";

    return $user;
}

// while(1){
    if(!empty($_GET['template'])){
        $template = $_GET['template'];

    $database = new Database_PDO;

    $query = $database->get_connection()->prepare("SELECT * FROM chat_users WHERE username LIKE :template");
    $query->execute(array('template' => $template."%"));

    $users = array();

    while($row = $query->fetch()) {
        $user = make_user($row['username'], '/img/anonymus.png', 'Hey, bro. You\'re..');
        array_push($users, $user);
    }

    echo json_encode($users);

    }
// }

 ?>
