<?php
require_once "session.inc.php";
require_once "secure.inc.php";
?>
<!DOCTYPE HTML>

<html>
<head>
    <title>Создание пользователя</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>

<body>
<h1>Создание пользователя</h1>
<?php
$login = 'root';
$password = '1234';
$result = '';

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $login = $_POST['login'] ?: $login;
    if(!user_exists($login)){
        $password = $_POST['password'] ?: $password;
        $hash = get_hash($password);
        if(save_user($login, $hash))
            $result = 'Хеш '. $hash. ' успешно добавлен в файл';
        else
            $result = 'При записи хеша '. $hash. ' произошла ошибка';
    }else{
        $result = "Пользователь $login уже существует. Выберите другое имя.";
    }
}
?>
    <h3><?php echo $result; ?></h3>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div>
            <label for="txtUser">Логин</label>
            <input id="txtUser" type="text" name="login" value="<?php echo $login; ?>" style="width:20em"/>
	</div>
	<div>
            <label for="txtString">Пароль</label>
            <input id="txtString" type="text" name="password" value="<?php echo $password; ?>" style="width:20em"/>
	</div>
	<div>
                    <button type="submit">Создать</button>
	</div>	
    </form>
</body>
</html>