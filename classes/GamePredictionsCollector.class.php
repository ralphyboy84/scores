<?php

require_once("User.class.php");
require_once("Prediction.class.php");

class GamePredictionsCollector
{
    private $dbObject;
    private $gameObject;
    private $userPredictions = array();
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }   
    
    public function setGameObject($obj)
    {
        $this->gameObject = $obj;   
    }
    
    public function getGameObject()
    {
        return $this->gameObject;   
    }
    
    public function setUserPredictions($user, $prediction, $key)
    {
        $this->userPredictions[$key]['user'] = $user;   
        $this->userPredictions[$key]['prediction'] = $prediction;  
    }
    
    public function getUserPredictions()
    {
        return $this->userPredictions;   
    }
    
    public function getPredictionsForGame()
    {
        $dbObject = $this->getDbObject();
        $sql =  "SELECT * FROM userguess WHERE gameid = '".$this->getGameObject()->getGameId()."' ORDER BY points DESC, homescore ASC, awayscore ASC";
        $res = $dbObject->performSelectQuery($sql);
        
        $x=0;
        
        if ($res) {
            foreach ($res as $vals) {
                $user = new User($vals['userid'], $dbObject);
                $prediction = new Prediction();
                $prediction->setPredictedHomeScore($vals['homescore']);
                $prediction->setPredictedAwayScore($vals['awayscore']);
                $prediction->setPoints($vals['points']);
                
                $this->setUserPredictions($user, $prediction, $x);
                $x++;            
            }
        }
    }
    
    public function returnHtmlPredictions()
    {
        $userPredictions = $this->getUserPredictions();
        
        if ($userPredictions) {
            foreach ($userPredictions as $vals) {
                $row[] = "<tr><td>".$vals['user']->getUsername()."</td><td>".$vals['prediction']->getPredictedHomeScore()."</td><td> - </td><td>".$vals['prediction']->getPredictedAwayScore()."</td><td>".$vals['prediction']->getPoints()."</td></tr>";
            }
        }
        
        if ($row) {
            $display = $this->getGameObject()->returnGameDisplay();
            
            return $display."<br /><br /><table style='font-size:12px'><thead><tr><th>User</th><th>Home Score</th><th>&nbsp;</th><th>Away Score</th><th>Points</th></tr></thead><tbody>".implode($row)."</tbody></table>";   
        }
    }
}

?>