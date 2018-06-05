<?php

require_once("classes/Game.class.php");
require_once("classes/DateFormat.class.php");

class TodaysGames
{
    private $dbObject;
    private $gameObjects;
    private $dateToUse;
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }   
    
    public function setGamesObjects($key, $obj)
    {
        $this->gameObjects[$key] = $obj;   
    }
    
    public function getGamesObjects($key = false)
    {
        if ($key) {
            return $this->gameObjects[$key];   
        } else {
            return $this->gameObjects;   
        }
    }
    
    public function setDate($val)
    {
        $this->dateToUse = $val;   
    }
    
    public function getDate()
    {
        return $this->dateToUse;   
    }
    
    public function getTodaysGames()
    {
        $dbObject = $this->getDbObject();
        $sql = "SELECT * FROM games WHERE matchdate like '%".$this->getDate()."%' ORDER BY matchdate ASC";
        $games = $dbObject->performSelectQuery($sql);

        if ($games) {
            foreach ($games as $gameInfo) {
                $game = new Game();
                $game->setHomeTeam($gameInfo['hometeam']);
                $game->setAwayTeam($gameInfo['awayteam']);
                $game->setMatchDate($gameInfo['matchdate']);
                $game->setGameId($gameInfo['gameid']);
                $game->setDateObject(new DateFormat());
                $this->setGamesObjects($gameInfo['gameid'], $game);
            }
        }
    }
    
    public function returnTodaysGamesHtml()
    {
        $gameObjects = $this->getGamesObjects();
        
        if ($gameObjects) {
            foreach ($gameObjects as $game) {
                $row[] = $game->returnGameDisplay($game);
            }
            
            if ($row) {
                return implode($row, "<br /><br />");   
            }
        } else {
            return "There are no games to be played today :(";   
        }
    }
}

?>