<?php

require_once("classes/Game.class.php");
require_once("classes/Database.class.php");

$_POST['hometeam'] = "England";
$_POST['awayteam'] = "Wales";
$_POST['homeScore'] = "3";
$_POST['awayScore'] = "0";

$database = new Database();

$game = new Game();
$game->setDbObject($database);
$game->setHomeTeam($_POST['hometeam']);
$game->setAwayTeam($_POST['awayteam']);
$game->setHomeScore($_POST['homeScore']);
$game->setAwayScore($_POST['awayScore']);
echo $game->updateGame();

?>