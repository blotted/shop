<?php
define("FILE_NAME", ".htpasswd");

function get_hash($password) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    return trim($hash);
}

function check_hash($password, $hash) {
    return password_verify(trim($password), trim($hash)); 
}

function save_user($login, $hash) {
    $str = "$login:$hash\n";
    if(file_put_contents(FILE_NAME, $str, FILE_APPEND)) {
        return true;
    } else {
        return false;
    }
}

function user_exists($login) {
    if(!is_file(FILE_NAME)) {
        return false;
    }
    $users = file(FILE_NAME);
    foreach ($users as $user) {
        if(strpos($user, $login.':') !== false) {
            return $user;
        }
    }
    return false;
}

function log_out(){
   session_destroy();
    header('Location: secure/login.php');
    exit;
}