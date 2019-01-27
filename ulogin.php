<?php

if(empty($_POST['token'])) {
    header("Location: /login.php");
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

require("./config/db.php");
session_start();

$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
$user = json_decode($s);

//$user['network'] - соц. сеть, через которую авторизовался пользователь
//$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
//$user['first_name'] - имя пользователя
//$user['last_name'] - фамилия пользователя

$login = $user->network . $user->uid;
$hash = password_hash(generateRandomString(), PASSWORD_BCRYPT);
$email = $user->email;
$name = $user->first_name . " " . $user->last_name;
$sex = $user->sex;
$photo = $user->photo_big;
$bday = date("Y-m-d", strtotime($user->bdate));
$today = date("Y-m-d");
$city = $user->original_city;

$sql = "SELECT * FROM users WHERE login=".$login;
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    $_SESSION['login_session'] = $login;
    header("Location: /index.php");
} else {
    $sql = "INSERT INTO `users` (login, password, confirm, email, regday, name, sex, bday, city, photo) 
  VALUES('$login', '$hash', 'Y', '$email', '$today', '$name', '$sex', '$bday', '$city', '$photo')";
    $result = $mysqli->query($sql);
    $_SESSION['login_session'] = $login;
    header("Location: /index.php");
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Авторизация через соцсети</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link type="text/css" rel="stylesheet" href="/assets/css/main.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/font-awesome.css">
</head>
<body>
<div _ngcontent-c1="" class="auth">
    <div _ngcontent-c1="" class="auth__block">
        <div _ngcontent-c1="" class="auth__content">
            <h1 _ngcontent-c1="" class="auth__logo">СООБЩНИК</h1>
            <br>
            <div _ngcontent-c1="" class="auth__form">
                <router-outlet _ngcontent-c1=""></router-outlet><app-signin _nghost-c2="">
                    <div _ngcontent-c2="" class="signin">
                        <div _ngcontent-c2="" class="form-group">
                            <span _ngcontent-c2=""><?php echo $message?></span>
                        </div>
                    </div>
                </app-signin>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/assets/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/assets/js/functions.js"></script>
<script type="text/javascript" src="/assets/js/validator.js"></script>

</body>
</html>