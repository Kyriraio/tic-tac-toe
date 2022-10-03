<?php

use App\Player\Player;
use App\Database\DB;
require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$db = new DB("gamedb");

$player = new Player($db);
$player->auth();
