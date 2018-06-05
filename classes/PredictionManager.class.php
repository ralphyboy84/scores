<?php

class PredictionManager
{
    private $predictionObj;
    private $gameObj;
    private $gamePoints;
    private $dbObject;
    
    public function setPredictionObj($obj)
    {
        $this->predictionObj = $obj;
    }
    
    public function getPredictionObj()
    {
        return $this->predictionObj;
    }
    
    public function setGameObj($obj)
    {
        $this->gameObj = $obj;
    }
    
    public function getGameObj()
    {
        return $this->gameObj;
    }
    
    public function setGamePoints($val)
    {
        $this->gamePoints = $val;   
    }
    
    public function getGamePoints()
    {
        return $this->gamePoints;   
    }
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }
    
    public function calculateOutcome()
    {
        $prediction = $this->getPredictionObj();
        $game = $this->getGameObj();
        
        if (!is_null($game->getHomeScore()) && !is_null($game->getAwayScore())) {    
            //if the score is completely correct award 5 points
            if ($prediction->getPredictedHomeScore() === $game->getHomeScore() && 
                $prediction->getPredictedAwayScore() === $game->getAwayScore()) {
                $this->setGamePoints(6);   
                return;
            //if the match was a draw then award 3 points for guessing the outcome correctly
            } else if ($prediction->getPredictedHomeScore() === $prediction->getPredictedAwayScore() &&
                $game->getHomeScore() === $game->getAwayScore()) {
                $this->setGamePoints(3);
            //if the match was predicited a home win and the user guessed the outcome correctly then award 3 points
            } else if ($prediction->getPredictedHomeScore() > $prediction->getPredictedAwayScore() &&
                $game->getHomeScore() > $game->getAwayScore()) {
                $this->setGamePoints(3);
            //if the match was predicited an away win and the user guessed the outcome correctly then award 3 points
            } else if ($prediction->getPredictedHomeScore() < $prediction->getPredictedAwayScore() &&
                $game->getHomeScore() < $game->getAwayScore()) {
                $this->setGamePoints(3);
            } else {
                $this->setGamePoints(0);   
            }

            //if the prediction guesses only the home team score award 1 point
            if ($prediction->getPredictedHomeScore() === $game->getHomeScore() && 
                $prediction->getPredictedAwayScore() !== $game->getAwayScore()) {
                $this->setGamePoints(1+$this->getGamePoints()); 
            //if the prediction guesses only the away team score award 1 point    
            } else if ($prediction->getPredictedHomeScore() !== $game->getHomeScore() && 
                $prediction->getPredictedAwayScore() === $game->getAwayScore()) {
                $this->setGamePoints(1+$this->getGamePoints()); 
            }
        }
    }
    
    public function updateUserGuess()
    {
        $dbObject = $this->getDbObject();
        $gameObject = $this->getGameObj();
        $predictionObject = $this->getPredictionObj();
        $userObject = $predictionObject->getUserObj();
        
        if (!is_null($this->getGamePoints())) {
            $sql = "UPDATE userguess SET points = '".$dbObject->quote($this->getGamePoints())."'
            WHERE userid = '".$dbObject->quote($userObject->getUser())."' 
            AND gameid = '".$dbObject->quote($gameObject->getGameId())."' ";

            $dbObject->performUpdateQuery($sql);
        }
    }
}

?>