<?php
use App\Bot\Bot;
use App\DataBase\DB;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$db = new DB("gamedb");

$bot = new Bot($db);
$bot->ask();
