<?php
// подключение библиотек
require "inc/lib.inc.php";
require "inc/db.inc.php";

$id = clear_int($_GET['id']);
if ($id){
    delete_from_basket($id);
    header("Location: basket.php");
    exit;
}
