<?php

require_once("../classes/User.class.php");
require_once("../classes/UserScores.class.php");
require_once("../classes/Database.class.php");
require_once("../classes/DateFormat.class.php");
require_once("../classes/CustomException.class.php");

try {
    $user = new User();
    $user->setUser($_POST['userid']);

    $us = new UserScores();
    $us->setDbObject(new Database());
    $us->setUserObject($user);
    $us->setDateObject(new DateFormat());
    $us->getUserScores();
    echo $us->generateHtmlInput();
}
catch (CustomException $e) {
    echo $e->errorMessage();
}   
?>