<?php

require_once("classes/TodaysGames.class.php");
require_once("classes/Database.class.php");

$games = new TodaysGames();
$games->setDbObject(new Database);
$games->setDate(date("Y-m-d"));
$games->getTodaysGames();

echo $games->returnTodaysGamesHtml();
?>