<?php

require_once("classes/User.class.php");
require_once("classes/Database.class.php");

$user = new User();
$user->setUser(103748);
$user->setDbObject(new Database());
$user->calculatePointsTotal();

echo $user->getPointsTotal();
?>