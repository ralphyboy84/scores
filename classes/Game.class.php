<?php

class Game
{
    private $dbObject;
    private $gameId;
    private $matchDate;
    private $homeTeam;
    private $awayTeam;
    private $homeScore;
    private $awayScore;
    private $dateObject;
    private $matchGroup;
    
    function __construct($gameId = false, $dbObject = false, $dateObject = false)
    {        
        if ($gameId && $dbObject) {
            $this->setDbObject($dbObject);
            $this->setGameId($gameId);
            
            $sql = "SELECT * FROM games WHERE gameid = '$gameId'";
            $res = $dbObject->performSelectQuery($sql);
            
            if ($res) {
                $this->setMatchDate($res[0]['matchdate']);
                $this->setHomeTeam($res[0]['hometeam']);
                $this->setAwayTeam($res[0]['awayteam']);
                $this->setHomeScore($res[0]['homescore']);
                $this->setAwayScore($res[0]['awayscore']);
                $this->setMatchGroup($res[0]['group']);
            }
        }
        
        $this->setDateObject($dateObject);
    }
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }
    
    public function setGameId($val)
    {
        $this->gameId = $val;   
    }
    
    public function getGameId()
    {
        return $this->gameId;   
    }
    
    public function setMatchDate($val)
    {
        $this->matchDate = $val;   
    }
    
    public function getMatchDate()
    {
        return $this->matchDate;    
    }
    
    public function setHomeTeam($val)
    {
        $this->homeTeam = $val;
    }
    
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }
    
    public function setAwayTeam($val)
    {
        $this->awayTeam = $val;
    }
    
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }
    
    public function setHomeScore($val)
    {
        $this->homeScore = $val;
    }
    
    public function getHomeScore()
    {
        return $this->homeScore;
    }
    
    public function setAwayScore($val)
    {
        $this->awayScore = $val;
    }
    
    public function getAwayScore()
    {
        return $this->awayScore;
    }
    
    public function setMatchGroup($val)
    {
        $this->matchGroup = $val;
    }
    
    public function getMatchGroup()
    {
        return $this->matchGroup;
    }
    
    public function saveGame()
    {
        $args = $this->getAllParams();

        $dbObject = $this->getDbObject();  
        $dbObject->perfomInsertQuery($args, "games");
    }
    
    public function setDateObject($obj)
    {
        $this->dateObject = $obj;   
    }
    
    public function getDateObject()
    {
        return $this->dateObject;   
    }
    
    public function updateGame()
    {
        $args = $this->getAllParams();
        
        $dbObject = $this->getDbObject(); 
        
        $sql = "UPDATE games SET homescore = '".$dbObject->quote($args['homescore'])."', 
        awayscore = '".$dbObject->quote($args['awayscore'])."' 
        WHERE hometeam = '".$args['hometeam']."' 
        AND awayteam = '".$args['awayteam']."' ";
        
        $dbObject->performUpdateQuery($sql);
    }
    
    private function getAllParams()
    {
        $args['hometeam'] = $this->getHomeTeam();
        $args['awayteam'] = $this->getAwayTeam();
        $args['homescore'] = $this->getHomeScore();
        $args['awayscore'] = $this->getAwayScore(); 
        
        return $args;   
    }
    
    public function saveGameInputScreen()
    {        
        $homeTeam = $this->getHomeTeam();
        $awayTeam = $this->getAwayTeam();
        
        return<<<EOHTML
        <form id='resultsForm'>
        $homeTeam <input type='hidden' id='homeTeam' name='homeTeam' value='$homeTeam' /><input type='input' id='homeScore' /><br />
        $awayTeam <input type='hidden' id='awayTeam' name='awayTeam' value='$awayTeam' /><input type='input' id='awayScore' /><br />
        </form>
        <button type='submit'>Hit me</button>
EOHTML;
    }

    public function returnGameDisplay()
    {
        $homeTeam = $this->getHomeTeam();
        $awayTeam = $this->getAwayTeam();
        $matchDate = $this->getMatchDate();
        $gameId = $this->getGameId();
        
        $dateObject = $this->getDateObject();
        $dateArgs = explode(" ", $matchDate);
        $time = $dateArgs[1];
        $dateObject->setDate($dateArgs[0]);
        $date = $dateObject->formatDateFromDatabase();
        
        $imgHome = strtolower($homeTeam);
        $imgAway = strtolower($awayTeam);
        
        return<<<EOHTML
        <span class='rowClass' id='$gameId'>
            <img src='img/$imgHome.gif' alt='$homeTeam Flag' class='flag' />$homeTeam vs $awayTeam <img src='img/$imgAway.gif' alt='$awayTeam Flag' class='flag' /> <br />
            $date $time
        </span>
EOHTML;
    }
}

?>