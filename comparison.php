<?php

require_once("classes/User.class.php");
require_once("classes/Database.class.php");
require_once("classes/UserScores.class.php");

$dbObject = new Database();

$player1 = new User(104709, $dbObject);
$player2 = new User(81916, $dbObject);
$player3 = new User(995714, $dbObject);

$player1Scores = new UserScores();
$player1Scores->setUserObject($player1);
$player1Scores->setDbObject($dbObject);
$player1Scores->getUserScores();

$player2Scores = new UserScores();
$player2Scores->setuserObject($player2);
$player2Scores->setDbObject($dbObject);
$player2Scores->getUserScores();

$player3Scores = new UserScores();
$player3Scores->setuserObject($player3);
$player3Scores->setDbObject($dbObject);
$player3Scores->getUserScores();

require_once("classes/Comparison.class.php");

$comparison = new Comparison();
$comparison->setUserScoreObjects($player1->getUser(), $player1Scores);
$comparison->setuserScoreObjects($player2->getUser(), $player2Scores);
$comparison->setuserScoreObjects($player3->getUser(), $player3Scores);
echo $comparison->showComparison();

?>