<?php

require_once("classes/Database.class.php");
require_once("classes/Leaderboard.class.php");

$leaderboard = new Leaderboard();
$leaderboard->setDbObject(new Database());
$leaderboard->generateLeaderboard();
echo $leaderboard->displayLeaderboardAsTable();

?>