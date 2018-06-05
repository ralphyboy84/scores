<?php

require_once("classes/Game.class.php");

$game = new Game();
$game->setHomeTeam("England");
$game->setAwayTeam("Wales");
echo $game->saveGameInputScreen();

?>