<?php

require_once("classes/PredictionManager.class.php");
require_once("classes/Prediction.class.php");
require_once("classes/Game.class.php");
require_once("classes/User.class.php");

class Calculator
{
    private $dbObject;
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    } 
    
    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    public function generateScores()
    {
        $dbObject = $this->getDbObject();
        
        $sql = "SELECT id as userid FROM users";
        $userList = $dbObject->performSelectQuery($sql);
        
        if ($userList) {
            foreach ($userList as $userInfo) {
                $user = new User();
                $user->setUser($userInfo['userid']);

                $sql = "SELECT * FROM userguess WHERE userid = '".$userInfo['userid']."'";
                $matchList = $dbObject->performSelectQuery($sql);
                
                if ($matchList) {
                    foreach ($matchList as $match) {
                        $prediction = new Prediction();
                        $prediction->setUserObj($user);
                        $prediction->setPredictedHomeScore($match['homescore']);
                        $prediction->setPredictedAwayScore($match['awayscore']);

                        $sql = "SELECT * FROM games WHERE gameid = '".$match['gameid']."'";
                        $gameOutcome = $dbObject->performSelectQuery($sql);
                        
                        $game = new Game();
                        $game->setHomeScore($gameOutcome[0]['homescore']);
                        $game->setAwayScore($gameOutcome[0]['awayscore']);
                        $game->setGameId($match['gameid']);

                        $pm = new PredictionManager();
                        $pm->setPredictionObj($prediction);
                        $pm->setGameObj($game);
                        $pm->setDbObject(new Database());
                        $pm->calculateOutcome();
                        $pm->updateUserGuess(); 
                    }
                }
            }
        }
    }
    
    public function updateHistory()
    {
        $dbObject = $this->getDbObject();
        
        $sql = "SELECT MAX(runid) as maxrunid FROM history";
        $maxRun = $dbObject->performSelectQuery($sql);
        $maxRunId = $maxRun[0]['maxrunid'];
        
        $sql = "SELECT SUM(points) as points, userid, name FROM userguess, users WHERE userguess.userid = users.id GROUP BY userid ORDER BY SUM(points) DESC";   
    
        $results = $dbObject->performSelectQuery($sql);
        
        if ($results) {
            $x=1;
            foreach ($results as $res) {
                $args['userid'] = $res['userid'];
                $args['position'] = $x;
                $args['runid'] = $maxRunId + 1;
                $dbObject->perfomInsertQuery($args, "history");   
                $x++;
            }
        }
    }
}

?>