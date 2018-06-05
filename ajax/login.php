<?php

session_start();

require_once("../classes/User.class.php");
require_once("../classes/Database.class.php");

$user = new User();
$user->setUsername($_POST['name']);
$user->setPassword($_POST['password']);
$user->setDbObject(new Database());
echo $user->loginUser();

?>