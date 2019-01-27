<?php

session_start();

require("./config/db.php");
if(isset($_SESSION['login_session'])) {
    header("Location: /index.php");
}

if(isset($_POST['submit_button'])) {
    if(!empty($_POST['login']) && !empty($_POST['password'])) {
        $login = htmlspecialchars($_POST['login']);
        $pass = htmlspecialchars($_POST['password']);
        $sql = "SELECT * FROM users WHERE login='$login'";
        $result = $mysqli->query($sql);
        if ($result->num_rows < 1) {
            $message = "Этого пользователя на сайте не существует!";
        } else {
            $user = $result->fetch_assoc();
            $hash = password_verify($pass, $user['password']);
            if ($hash === true) {
                if ($user['confirm'] != 'Y'){
                    $message = "Аккаунт не был подтверждён. Проверьте электронную почту.";
                } else {
                    $_SESSION['login_session'] = $login;
                    header("Location: /index.php");
                }
            } else {
                $message = "Пароль неправильный! Попробуйте ещё раз.";
            }
        }
    } else {
        $message = "Логин и пароль не могут быть пустыми!";
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Авторизация</title>
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
            <h3 _ngcontent-c1="" class="auth__social_title">Войти через социальные сети:</h3>
            <div id="uLogin" data-ulogin="display=panel;theme=classic;fields=first_name,last_name,nickname,email,bdate,sex,photo_big,city;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=twitter,google,yandex;redirect_uri=http%3A%2F%2Fshatiger.beget.tech%2Fulogin.php;mobilebuttons=0;"></div>
            <br>
            <div _ngcontent-c1="" class="auth__or_block">
                <div _ngcontent-c1="" class="auth__or">или</div>
            </div>
            <div _ngcontent-c1="" class="auth__form">
                <router-outlet _ngcontent-c1=""></router-outlet><app-signin _nghost-c2="">
                    <form _ngcontent-c2="" class="signin" action="/login.php" method="post" name="login_form">
                        <div _ngcontent-c2="" class="form-group">
                            <span _ngcontent-c2="" class="error-message"><?php echo $message?></span>
                        </div>
                        <div _ngcontent-c2="" class="form-group">
                            <label _ngcontent-c2="" for="login">Псевдоним или электронная почта:</label>
                            <input _ngcontent-c2="" class="form-control" name="login" id="login">
                        </div>

                        <div _ngcontent-c2="" class="form-group">
                            <label _ngcontent-c2="" for="password">Пароль:</label>
                            <input _ngcontent-c2="" class="form-control" id="password" name="password" type="password">
                        </div>

                        <div _ngcontent-c2="" class="signin__remember">
                            <span _ngcontent-c2="">Запомнить меня</span>
                            <label _ngcontent-c2="" class="checkbox">
                                <input _ngcontent-c2="" type="checkbox">
                                <i _ngcontent-c2=""></i>
                            </label>
                        </div>

                        <div _ngcontent-c2="" class="signin__controls">
                            <input type="hidden" name="auth" value="1">
                            <button _ngcontent-c2="" class="btn btn-block btn-lg" type="submit" name="submit_button">
                                Войти
                            </button>
                            <a _ngcontent-c2="" class="text-primary" href="/restore.php">Напомнить пароль</a>
                        </div>

                        <div _ngcontent-c2="" class="signin__footer">
                            <p _ngcontent-c2="">Еще не зарегистировались?</p>
                            <a _ngcontent-c2="" class="text-primary" href="/register.php">Регистрация за 30 секунд</a>
                        </div>
                    </form>



                </app-signin>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/assets/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/assets/js/functions.js"></script>
<script type="text/javascript" src="/assets/js/validator.js"></script>
<script src="//ulogin.ru/js/ulogin.js"></script>

</body>
</html>