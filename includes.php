<?php

/*if ($_SERVER['HTTP_HOST'] == "localhost") {
    session_start();
    $usercookie = false;
    
    if ($_SESSION['usercookie']) {
        $usercookie = $_SESSION['usercookie'];
    }
} else {*/
    require_once("focus/sessionauthmp.php");   
//}

//$_SESSION['usercookie'] = 1342120;
$usercookie = $_SESSION['usercookie'];

?>