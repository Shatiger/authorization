<?php

session_start();

require("./config/db.php");
if(isset($_SESSION['login_session'])) {
    header("Location: /index.php");
}

if(!empty($_GET['login']) && !empty($_GET['confirm'])) {
    $login = $_GET['login'];
    $sql = "SELECT * FROM users WHERE login='$login'";
    $result = $mysqli->query($sql);
    if ($result->num_rows < 1) {
        $message = "Этого пользователя на сайте не существует!";
    } else {
        $user = $result->fetch_assoc();
        if($user['confirm'] == 'Y') {
            $message = "Пользователь уже ранее был подтверждён! Нажмите <a href='/login.php'>сюда</a>, чтобы перейти к авторизации.";
        } else {
            if($_GET['confirm'] == $user['confirm']) {
                $userid = $user['id'];
                $sql = "UPDATE users SET confirm='Y' WHERE id = '$userid'";
                $result = $mysqli->query($sql);
                $message = "Пользователь подтверждён! Нажмите <a href='/login.php'>сюда</a>, чтобы перейти к авторизации.";
            } else {
                $message = "Код подтверждения неправильный! Проверьте ссылку.";
            }
        }
    }
} else {
    header("Location: /login.php");
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Подтверждение пользователя</title>
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
