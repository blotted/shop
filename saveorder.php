<?php
require "inc/lib.inc.php";
require "inc/db.inc.php";

$name = clear_str($_POST['name']);
$email = clear_str($_POST['email']);
$phone = clear_str($_POST['phone']);
$address = clear_str($_POST['address']);
$oid = $basket['orderid'];
$dt = time();

$order = "$name|$email|$phone|$address|$oid|$dt\n";
file_put_contents('admin/'.ORDERS_LOG, $order, FILE_APPEND);
save_order($dt);

    
?>
<html>
<head>
    <title>Сохранение данных заказа</title>
</head>
<body>
    <p>Ваш заказ принят.</p>
    <p><a href="catalog.php">Вернуться в каталог товаров</a></p>
</body>
</html>