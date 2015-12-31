<?php
$title = 'Авторизация';
$login  = '';
session_start();
header("HTTP/1.0 401 Unauthorized");
require_once 'secure.inc.php';
if($_SERVER['REQUEST_METHOD'] == "POST") {
    $login = trim(strip_tags($_POST['login']));
    $pw = trim(strip_tags($_POST['pw']));
    $ref = trim(strip_tags($_GET['ref']));
    if(!$ref) {
        $ref = '/shop_book/admin/';
    }
    if($login and $pw) {
        if($result = user_exists($login)){
            list($_, $hash) = explode(':', $result);
            if(check_hash($pw, $hash)){
                $_SESSION['admin'] = true;
                header("Location: $ref");
                exit;
            } else {
                $title = "Неправильный пароль.";
            }
        } else {
            $title = "Неправильное имя пользователя";
        }      
    } else {
        $title = "Заполните все поля";
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Авторизация</title>
    <meta charset="utf-8">
</head>
<body>
    <h1><?php echo $title; ?></h1>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        <div>
            <label for="txtUser">Логин</label>
            <input id="txtUser" type="text" name="login" value="<?php echo $login; ?>" />
        </div>
        <div>
            <label for="txtString">Пароль</label>
            <input id="txtString" type="password" name="pw" />
        </div>
        <div>
            <button type="submit">Войти</button>
        </div>	
    </form>
</body>
</html>