<?php

require_once("../includes.php");
require_once("../classes/Game.class.php");
require_once("../classes/Database.class.php");
require_once("../classes/GamePredictionsCollector.class.php");
require_once("../classes/DateFormat.class.php");

$game = new Game($_POST['id'], new Database(), new DateFormat());

$gpc = new GamePredictionsCollector();
$gpc->setGameObject($game);
$gpc->setDbObject(new Database());
$gpc->getPredictionsForGame();
echo $gpc->returnHtmlPredictions();
?>