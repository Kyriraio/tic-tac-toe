<?php
use App\DataBase\DB;

require_once __DIR__ . '/vendor/autoload.php';


session_start();

if(!isset($_SESSION['login']))
{
    header("Location:index.php");
}
 function getLevel()
 {
     $stmt = (new DB("gamedb"))->run("SELECT level FROM players WHERE login=?",[$_SESSION['login']]);
     return $stmt->fetch()['level'];
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
    <title>Tic-Tac-Bot</title>
</head>
<body>
<div class = "game-page page">
    <div class="table-title ">
        <div class="highlighted">
            <label id="player-name"><?php echo $_SESSION['login']; ?></label>
            vs
            <label id="bot-name"> TicTacBot</label></div>
        <div><label id="player-level"><?php echo getLevel() ?></label> Уровень</div>
    </div>
    <div class = "table">
        <div class = "tr">
            <div class = "td" data-id="0">
                <div class = "x" ></div>
                <div class = "o"></div>
            </div>
            <div class = "td" data-id="1">
                <div class = "x"></div>
                <div class = "o"></div>
            </div>
            <div class = "td" data-id="2">
                <div class = "x"></div>
                <div class = "o"></div>
            </div>

        </div>
        <div class = "tr">
            <div class = "td" data-id="3">
                <div class = "x"></div>
                <div class = "o"></div>
            </div>
            <div class = "td" data-id="4">
                <div class = "x"></div>
                <div class = "o"></div>
            </div>
            <div class = "td" data-id="5">
                <div class = "x"></div>
                <div class = "o"></div>
            </div>

        </div>
        <div class = "tr">
            <div class = "td" data-id="6">
                <div class = "x"></div>
                <div class = "o"></div>
            </div>
            <div class = "td" data-id="7">
                <div class = "x"></div>
                <div class = "o"></div>
            </div>
            <div class = "td" data-id="8">
                <div class = "x"></div>
                <div class = "o"></div>
            </div>

        </div>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="scripts/game.js"></script>
</body>
</html>