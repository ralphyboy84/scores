<?php

require_once("Prediction.class.php");

class PredictionsCollector
{
    private $postArgs;
    private $userObject;
    private $dbObject;
    
    public function setPostArgs($vals)
    {
        $this->postArgs = $vals;   
    }
    
    public function getPostArgs()
    {
        return $this->postArgs;   
    }
    
    public function setUserObject($obj)
    {
        $this->userObject = $obj;   
    }
    
    public function getUserObject()
    {
        return $this->userObject;   
    }
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }
    
    public function saveAllPredictions()
    {  
        if ($this->getPostArgs()) {
            foreach ($this->getPostArgs() as $key => $val) {
                $keyArgs = explode("_", $key); 
                $args[$keyArgs[1]][$keyArgs[0]] = $val;
            }
        }
        
        $dbObject = $this->getDbObject();

        if ($args) {
            foreach ($args as $key => $vals) {
                $prediction = new Prediction();
                $prediction->setDbObject($dbObject);
                $prediction->setUserObj($this->getUserObject());
                $prediction->setGameId($key);
                $prediction->setPredictedHomeScore($vals['homeSelect']);
                $prediction->setPredictedAwayScore($vals['awaySelect']);
                $prediction->decideWhatToDo();
            }
        }
    }
}

?>