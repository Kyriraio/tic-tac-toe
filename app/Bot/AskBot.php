<?php
use App\Bot\Bot;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$bot = new Bot();
$bot->getFieldInfo();
$bot->makeMove();
$bot->updateLevel();
$bot->echoResponse();