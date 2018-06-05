<?php

require_once("../includes.php");
require_once("../classes/PredictionsCollector.class.php");
require_once("../classes/User.class.php");
require_once("../classes/Database.class.php");

$user = new User();
$user->setUser($usercookie);

$pc = new PredictionsCollector();
$pc->setPostArgs($_POST);
$pc->setUserObject($user);
$pc->setDbObject(new Database());
$pc->saveAllPredictions();

echo "Thank you for making your predictions! You are able to update your predictions up until the day before the tournament starts";

?>