<?php
// подключение библиотек
require "secure/session.inc.php";
require "../inc/lib.inc.php";
require "../inc/db.inc.php";

$title = clear_str($_POST['title']);
$author = clear_str($_POST['author']);
$pubyear = clear_int($_POST['pubyear']);
$price = clear_int($_POST['price']);

if (!add_item_to_catalog($title, $author, $pubyear, $price)) {
    echo "Произошла ошибка при добавлении товара в каталог";
} else {
    header('Location: add2cat.php');
    exit;
}
?>