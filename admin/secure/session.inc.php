<?php
session_start();
if(!isset($_SESSION['admin'])){
    header('Location: /shop_book/admin/secure/login.php?ref='.$_SERVER['REQUEST_URI']);
    exit;
}