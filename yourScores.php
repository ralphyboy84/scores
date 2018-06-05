<?php

require_once("classes/UserScores.class.php");
require_once("classes/User.class.php");
require_once("classes/Database.class.php");

$user = new User();
$user->setUser(103748);

$us = new UserScores();
$us->setDbObject(new Database());
$us->setUserObject($user);
$us->getUserScores();
echo $us->generateHtmlInput();
?>