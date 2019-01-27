<?php

session_start();

require("./config/db.php");
if(isset($_SESSION['login_session'])) {
    header("Location: /index.php");
}

$message = "";

if(isset($_POST['reg'])) {
    if(!empty($_POST['login']) && !empty($_POST['password'])) {
        if(empty($_POST['password_conf'])) {
            $message .= 'Подтвердите пароль!<br>';
        } elseif($_POST['password'] != $_POST['password_conf']) {
            $message .= 'Пароли не совпадают!<br>';
        }
        if(empty($_POST['email'])) {
            $message .= 'Введите E-mail!<br>';
        }
        if(strtolower($_POST['captcha']) != 'ccga') {
            $message .= 'Капча введена неверно!<br>';
        }

        if(strlen($message) <= 0) {
            $login = htmlspecialchars($_POST['login']);
            $pass = htmlspecialchars($_POST['password']);
            $email = $_POST['email'];
            $sql = "SELECT * FROM users WHERE login=".$login;
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                $message = "Пользователь с таким логином на сайте уже зарегистрирован!";
            } else {
                $sql = "SELECT * FROM users WHERE email=".$email;
                $result = $mysqli->query($sql);
                if ($result->num_rows > 0) {
                    $message = "Пользователь с таким e-mail на сайте уже зарегистрирован!";
                }
            }

            if(strlen($message) <= 0) {
                $hash = password_hash($pass, PASSWORD_BCRYPT);
                $confirm = rand(100000, 999999);
                $today = date("Y-m-d");
                $sql = "INSERT INTO `users` (login, password, confirm, email, regday) VALUES('$login', '$hash', '$confirm', '$email', '$today')";
                $message = $sql;
                $result = $mysqli->query($sql);
                if($result){
                    $mail_content =
                        "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">".
                        "<html xmlns=\"http://www.w3.org/1999/xhtml\">".
                        "<head>".
                        "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />".
                        "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>".
                        "<title>Подтверждение регистрации</title>".
                        "</head>".
                        "<body>
                            Перейдите по ссылке, чтобы подтвердить аккаунт:
                            <a href='http://shatiger.beget.tech/confirm.php?login=$login&confirm=$confirm'>ссылка</a></body>".
                        "</html>";
                    mail($_POST['email'], "Подтверждение регистрации", $mail_content, "Content-type: text/html;");
                    header("Location: /thank-you.php");
                } else {
                    $message = "Что-то пошло не так. " . $mysqli->error;
                }
            }
        }
    } else {
        $message .= "Логин и пароль не могут быть пустыми!<br>";
    }
}

?>
<!DOCTYPE html>
<html lang="ru"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Регистрация</title>
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
            <h3 _ngcontent-c1="" class="auth__social_title">Регистрация:</h3>
            <div id="uLogin" data-ulogin="display=panel;theme=classic;fields=first_name,last_name,nickname,email,bdate,sex,photo_big,city;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=twitter,google,yandex;redirect_uri=http%3A%2F%2Fshatiger.beget.tech%2Fulogin.php;mobilebuttons=0;"></div>
            <br>
            <div _ngcontent-c1="" class="auth__or_block">
                <div _ngcontent-c1="" class="auth__or">или</div>
            </div>
            <div _ngcontent-c1="" class="auth__form">
                <router-outlet _ngcontent-c1=""></router-outlet><app-signup _nghost-c3="">
                    <form _ngcontent-c3="" class="signup" action="" method="post" name="register_form">

                        <div _ngcontent-c2="" class="form-group">
                            <span _ngcontent-c2="" class="error-message"><?php echo $message?></span>
                        </div>

                        <div _ngcontent-c3="" class="form-group">
                            <label _ngcontent-c3="" for="login">Псевдоним:</label>
                            <input _ngcontent-c3="" class="form-control" id="login" name="login" value="">
                        </div>

                        <div _ngcontent-c3="" class="form-group">
                            <label _ngcontent-c3="" for="email">Электронная почта:</label>
                            <input _ngcontent-c3="" class="form-control" id="email" type="email" name="email" value="">
                        </div>

                        <div _ngcontent-c3="" class="form-group">
                            <label _ngcontent-c3="" for="password">Пароль:</label>
                            <input _ngcontent-c3="" class="form-control" id="password" type="password" name="password">
                        </div>

                        <div _ngcontent-c3="" class="form-group">
                            <label _ngcontent-c3="" for="password-repeat">Пароль еще раз:</label>
                            <input _ngcontent-c3="" class="form-control" id="password-repeat" type="password" name="password_conf">
                        </div>

                        <div _ngcontent-c3="" class="form-group">
                            <label _ngcontent-c3="" for="captcha">Текст с картинки:</label>
                            <input _ngcontent-c3="" class="form-control" id="captcha" name="captcha">
                        </div>
                        <div style="text-align: center;">
                            <img src="/assets/img/captcha.php" id="captcha" style="cursor: pointer;" alt="">
                        </div>
                        <p _ngcontent-c3="" class="text-help signup__agreement">
                            Регистрируясь я подтверждаю свое  согласие с условиями <a _ngcontent-c3="" class="text-primary" href="/offer.pdf" target="_blank">пользовательского соглашения.</a>
                        </p>

                        <button _ngcontent-c3="" class="btn btn-block btn-lg signup__submit" type="submit" name="reg">
                            Зарегистрироваться
                        </button>

                        <div _ngcontent-c3="" class="signup__footer">
                            <p _ngcontent-c3="">Уже есть аккаунт?</p>
                            <a _ngcontent-c3="" class="text-primary" href="/login.php">Войти</a>
                        </div>

                    </form>
                </app-signup>
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