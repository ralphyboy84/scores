<?php

require_once("includes.php");
require_once("classes/ScopeDatabase.class.php");

$sql = "SELECT * FROM master WHERE psosno = $usercookie";

$scope = new ScopeDatabase();
$res = $scope->performSelectQuery($sql);

echo "<div class='w3-black'>".$res[0]['forename']." ".$res[0]['surname']."</div>";

?>