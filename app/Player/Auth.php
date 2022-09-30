<?php

use App\Player\Player;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$player = new Player();
$player->validate();
if($player->inputValid())
    $player->auth();
$player->echoResponse();
