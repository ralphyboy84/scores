<?php

require_once("../includes.php");

require_once("../classes/UserGroup.class.php");
require_once("../classes/Database.class.php");
require_once("../classes/CustomException.class.php");

try {
    $group = new UserGroup($_POST['group'], new Database(), $usercookie);
    $array = $group->generateLeagueTable();

    echo json_encode($array);
    echo $json;
}
catch (CustomException $e) {
    echo $e->errorMessage();
}   

?>