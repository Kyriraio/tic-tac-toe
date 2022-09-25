<?php
session_start();
if(isset($_SESSION['login']))
{
    header("Location:game.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="#">
    <title>Tic-Tac-God</title>
</head>
<body>
<div class = "login-page page">
    <div class = "form">
        <form id="auth-form">
            <span class="error" id="login-error"></span>
            <input name="login" type="text" placeholder="имя" autocomplete="on"/>
            <span class="error" id="password-error"></span>
            <input name="password" type="password" placeholder="пароль" autocomplete="on"/>
            <button id="auth">войти</button>
        </form>

    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="scripts/auth.js"></script>
</body>
</html>