<?php

class Prediction
{
    private $dbObject;
    private $userObject;
    private $predictedHomeScore;
    private $predictedAwayScore;
    private $gameId;
    private $points;
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }
    
    public function setUserObj($obj)
    {
        $this->userObject = $obj;   
    }
    
    public function getUserObj()
    {
        return $this->userObject;   
    }
    
    public function setPredictedHomeScore($val)
    {
        $this->predictedHomeScore = $val;
    }
    
    public function getPredictedHomeScore()
    {
        return $this->predictedHomeScore;
    }
    
    public function setPredictedAwayScore($val)
    {
        $this->predictedAwayScore = $val;
    }
    
    public function getPredictedAwayScore()
    {
        return $this->predictedAwayScore;
    }
    
    public function setGameId($val)
    {
        $this->gameId = $val;   
    }
    
    public function getGameId()
    {
        return $this->gameId;   
    }
    
    public function setPoints($val)
    {
        $this->points = $val;   
    }
    
    public function getPoints()
    {
        return $this->points;   
    }
    
    public function savePrediction()
    {
        $dbObject = $this->getDbObject();
        $args = $this->getParams();
        $dbObject->perfomInsertQuery($args, "userguess");
    }
    
    public function updatePrediction()
    {        
        $dbObject = $this->getDbObject(); 
        $args = $this->getParams();
        
        $sql = "UPDATE userguess SET homescore = '".$dbObject->quote($args['homescore'])."', 
        awayscore = '".$dbObject->quote($args['awayscore'])."' 
        WHERE userid = '".$args['userid']."' 
        AND gameid = '".$args['gameid']."' ";

        $dbObject->performUpdateQuery($sql);
    }
    
    private function checkPredictionExists()
    {
        $dbObject = $this->getDbObject(); 
        $args = $this->getParams();
        
        $sql = "SELECT * FROM userguess WHERE userid = '".$args['userid']."' AND gameid = '".$args['gameid']."' ";
        return $dbObject->performSelectQuery($sql);
    }
    
    public function decideWhatToDo()
    {
        $checkExists = $this->checkPredictionExists();
        
        if ($checkExists) {
            $this->updatePrediction();
        } else {
            $this->savePrediction();   
        }
    }
    
    private function getParams()
    {
        $args['userid'] = $this->getUserObj()->getUser();
        $args['gameid'] = $this->getGameId();
        $args['homescore'] = $this->getPredictedHomeScore();
        $args['awayscore'] = $this->getPredictedAwayScore();
        
        return $args;
    }
    
    public function getPrediction()
    {
        $dbObject = $this->getDbObject();
        
        $userId = $this->getUserObj()->getUser();
        $gameId = $this->getGameId();
        
        $sql = "SELECT * FROM userguess WHERE gameid = '$gameId' AND userid = '$userId'";
        
        $info = $dbObject->performSelectQuery($sql);
        
        if ($info) {
            $this->setPredictedHomeScore($info[0]['homescore']);  
            $this->setPredictedAwayScore($info[0]['awayscore']);   
            $this->setPoints($info[0]['points']);
        }
    }
}

?>