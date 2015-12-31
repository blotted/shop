<?php
// подключение библиотек
require "inc/lib.inc.php";
require "inc/db.inc.php";

$id = clear_int($_GET['id']);
$quantity = 1;
add_to_basket($id, $quantity);
header('Location: catalog.php');
exit;
?>