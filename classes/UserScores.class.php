<?php

require_once("Game.class.php");
require_once("Prediction.class.php");

class UserScores
{
    const ALLOWSCOREEDITING = true;
    
    private $userObject;
    private $gameObjects;
    private $dbObject;
    private $predictionObjects;
    private $dateObject;
    
    public function setUserObject($obj)
    {
        $this->userObject = $obj;   
    }
    
    public function getUserObject()
    {
        return $this->userObject;   
    }
    
    public function setGameObjects($key, $obj)
    {
        $this->gameObjects[$key] = $obj;   
    }
    
    public function getGameObjects($key = false)
    {
        if ($key) {
            return $this->gameObjects[$key];   
        } else {
            return $this->gameObjects;   
        }
    }
    
    public function setPredictionObjects($key, $obj)
    {
        $this->predictionObjects[$key] = $obj;   
    }
    
    public function getPredictionObjects($key = false)
    {
        if ($key) {
            return $this->predictionObjects[$key];   
        } else {
            return $this->predictionObjects;   
        }
    }
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }
    
    public function setDateObject($obj)
    {
        $this->dateObject = $obj;   
    }
    
    public function getDateObject()
    {
        return $this->dateObject;   
    }
    
    public function getUserScores()
    {
        $dbObject = $this->getDbObject();
        $sql = "SELECT * FROM games ORDER BY matchdate ASC";
        
        $games = $dbObject->performSelectQuery($sql);
        
        if ($games) {
            foreach ($games as $gameInfo) {
                //create the game object to get the details of the game
                $game = new Game();
                $game->setHomeTeam($gameInfo['hometeam']);
                $game->setAwayTeam($gameInfo['awayteam']);
                $game->setHomeScore($gameInfo['homescore']);
                $game->setAwayScore($gameInfo['awayscore']);
                $game->setMatchDate($gameInfo['matchdate']);
                $game->setMatchGroup($gameInfo['group']);
                $game->setGameId($gameInfo['gameid']);
                
                //set the game object to the gameObjects prop
                $this->setGameObjects($gameInfo['gameid'], $game);
                
                //create the prediction object to get the details of the users prediction for that game (if they have one)
                $prediction = new Prediction();
                $prediction->setGameId($gameInfo['gameid']);
                $prediction->setUserObj($this->getUserObject());
                $prediction->setDbObject($dbObject);
                $prediction->getPrediction();
                
                //set the prediction object to the predictionObjects prop
                $this->setPredictionObjects($gameInfo['gameid'], $prediction);
            }
        } else {
            throw new CustomException("No games returned from the database");   
        }
    }
    
    private function checkEditing()
    {
        $todaysDate = date("Y-m-d");
        
        //bit of code to allow people to edit up until the first day of the comp
        if (self::ALLOWSCOREEDITING && $todaysDate < "2018-06-14") {
            $allowEditing = true;   
        } else {
            $allowEditing = false;   
        } 
        
        return $allowEditing;
    }
    
    public function generateHtmlInputButton()
    {
        $allowEditing = $this->checkEditing();
        
        if ($allowEditing) {
            return "<br /><button id='submitForm' type='button'>Save Scores</button>";   
        }
    }
    
    public function generateHtmlInput()
    {        
        $allowEditing = $this->checkEditing();
        
        if ($this->getGameObjects()) {
            foreach ($this->getGameObjects() as $game) {
                
                //get the relevant prediction object for the relevant game object
                $predictionObj = $this->getPredictionObjects($game->getGameId());
                
                if (is_null($game->getHomeScore()) && $allowEditing) {
                    $row[] = $this->generateEditInput($game, $predictionObj); 
                } else if (is_null($game->getHomeScore()) && is_null($predictionObj->getPredictedHomeScore())) {
                    $row[] = $this->generateEditInput($game, $predictionObj);
                } else {
                    $row[] = $this->generateReadOnlyInput($game, $predictionObj); 
                }
            }
                    
            if ($row) {
                return "<table>
                    <tr>
                        <th>Group</th>
                        <th>Match Date</th>
                        <th>Home Team</th>
                        <th>Actual Score</th>
                        <th>Predicted Score</th>
                        <th>&nbsp;</th>
                        <th>Away Team</th>
                        <th>Actual Score</th>
                        <th>Predicted Score</th>
                        <th>Points</th>
                    </tr>
                        ".implode($row)."</table>";
            }   
        } else {
            throw new CustomException("No game objects found");   
        }
    }
    
    private function generateEditInput($game, $prediction)
    {    
        if (!is_null($prediction->getPredictedHomeScore())) {
            $defaultHome = $prediction->getPredictedHomeScore();
        }
            
        if (!is_null($prediction->getPredictedAwayScore())) {
            $defaultAway = $prediction->getPredictedAwayScore();
        }
        
        $gameId = $game->getGameId();
        
        $homeSelect = $this->generateSelectBox($prediction->getPredictedHomeScore(), "homeSelect_$gameId");
        $awaySelect = $this->generateSelectBox($prediction->getPredictedAwayScore(), "awaySelect_$gameId");

        return $this->templateRow($game, $prediction, $homeSelect, $awaySelect);
    }
    
    private function templateRow($game, $prediction, $userHome, $userAway)
    {
        $homeTeam = $game->getHomeTeam();
        $awayTeam = $game->getAwayTeam();
        $homeScore = $game->getHomeScore();
        $awayScore = $game->getAwayScore();
        $gameId = $game->getGameId();
        $matchDate = $game->getMatchDate();
        $matchGroup = $game->getMatchGroup();
        
        $points = $prediction->getPoints();
        
        $dateTime = $this->formatDate($matchDate);
        $date = $dateTime['date'];
        $time = $dateTime['time'];
        
        return<<<EOHTML
        <tr>
            <td class='groupClass' data-group='$matchGroup'>$matchGroup</td>
            <td class='rowClass' id='$gameId'>$date $time</td>
            <td><img src='img/$homeTeam.gif' alt='$homeTeam' class='smallFlag' />$homeTeam <input type='hidden' id='homeTeam_$gameId' name='homeTeam_$gameId' value='$homeTeam' /></td>
            <td>&nbsp; $homeScore</td>
            <td style='color:red;font-weight:bold'>$userHome</td>
            <td>vs.</td>
            <td><img src='img/$awayTeam.gif' alt='$awayTeam' class='smallFlag' />$awayTeam <input type='hidden' id='awayTeam_$gameId' name='awayTeam_$gameId' value='$awayTeam' /></td>
            <td>&nbsp; $awayScore</td>
            <td style='color:red;font-weight:bold'>$userAway</td>
            <td>&nbsp; $points</td>
        </tr>
EOHTML;
    }
    
    private function generateSelectBox($default, $label)
    {
        for ($x=0; $x<=10; $x++) {
            if ($x==$default) {
                $selected = "selected='selected'";   
            } else {
                $selected = "";   
            }
            
            $optionArgs[] = "<option value='$x' $selected>$x</option>";
        }
        
        return "<select id='$label' name='$label'>".implode($optionArgs)."</select>";
    }
                    
    private function generateReadOnlyInput($game, $prediction)
    {
        $predictedHome = $prediction->getPredictedHomeScore();
        $predictedAway = $prediction->getPredictedAwayScore();
        
        return $this->templateRow($game, $prediction, $predictedHome, $predictedAway);
    }
    
    private function formatDate($matchDate)
    {
        $dateObject = $this->getDateObject();
        $dateArgs = explode(" ", $matchDate);
        $time = $dateArgs[1];
        $dateObject->setDate($dateArgs[0]);
        
        return array ( 
            'date' => $dateObject->formatDateFromDatabase(),
            'time' => $time
        );   
    }
}

?>