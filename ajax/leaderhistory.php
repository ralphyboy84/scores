<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("../includes.php");

require_once("../classes/Database.class.php");
require_once("../classes/Leaderboard.class.php");

$lb = new Leaderboard();
$lb->setDbObject(new Database());
$res = $lb->generateLeaderHistory();

$games = array();

if ($res) {
    foreach ($res as $vals) {
        if (!in_array($vals['runid'], $games)) {
            $games[] = $vals['runid'];
        }

        $user[$vals['name']][] = $vals['position'];
    }
}

$xx['games'] = $games;
$xx['users'] = $user;

echo json_encode($xx);

?>