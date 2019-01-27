<?php
session_start();
unset($_SESSION['login_session']);
session_destroy();
header("Location: /login.php");
?>