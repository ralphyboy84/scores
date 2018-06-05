<?php

require_once("Game.class.php");

class UserGroup
{
    private $teams;
    private $gameObjects;
    private $group;
    private $dbObject; 
    
    public function __construct($group = false, $dbObject = false, $user)
    {
        if ($group && $dbObject) {
            $this->setGroup($group);
            $this->setDbObject($dbObject);
            
            $sql = "SELECT * FROM games, userguess WHERE `group` = '$group' AND games.gameid = userguess.gameid AND userguess.userid = $user";
            $res = $dbObject->performSelectQuery($sql);
            
            if ($res) {
                foreach ($res as $vals) {
                    $game = new Game();
                    $game->setHomeTeam($vals['hometeam']);
                    $game->setAwayTeam($vals['awayteam']);
                    $game->setHomeScore($vals['homescore']);
                    $game->setAwayScore($vals['awayscore']);
                    $game->setMatchDate($vals['matchdate']);
                    $game->setMatchGroup($vals['group']);
                    $game->setGameId($vals['gameid']);
                    $this->setGameObjects($vals['gameid'], $game);
                }
            }
        }
    }
    
    public function setTeams($key = false, $val)
    {
        $this->teams[$key] = $val;
    }   
    
    public function getTeams($key)
    {
        if ($key) {
            return $this->teams[$key];
        } else {
            return $this->teams;   
        }
    }
    
    public function setGameObjects($key = false, $obj)
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
    
    public function setGroup($val)
    {
        $this->group = $val;   
    }
    
    public function getGroup()
    {
        return $this->group;   
    }
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }
    
    public function generateLeagueTable()
    {
        $games = $this->getGameObjects();
        
        if ($games) {
            foreach ($games as $game) {
                if (!is_null($game->getHomeScore()) && !is_null($game->getAwayScore())) {
                    if ($game->getHomeScore() > $game->getAwayScore()) {
                        $currHPoints = $team[$game->getHomeTeam()]['points'];
                        $team[$game->getHomeTeam()]['points'] = $currHPoints + 3; 
                        
                        $currAPoints = $team[$game->getAwayTeam()]['points'];
                        $team[$game->getAwayTeam()]['points'] = $currAPoints + 0;
                    } else if ($game->getHomeScore() < $game->getAwayScore()) {
                        $currHPoints = $team[$game->getHomeTeam()]['points'];
                        $team[$game->getHomeTeam()]['points'] = $currHPoints + 0; 
                        
                        $currAPoints = $team[$game->getAwayTeam()]['points'];
                        $team[$game->getAwayTeam()]['points'] = $currAPoints + 3;    
                    } else {
                        $currHPoints = $team[$game->getHomeTeam()]['points'];
                        $team[$game->getHomeTeam()]['points'] = $currHPoints + 1;  

                        $currAPoints = $team[$game->getAwayTeam()]['points'];
                        $team[$game->getAwayTeam()]['points'] = $currAPoints + 1;
                    }

                    $homeScored = $team[$game->getHomeTeam()]['scored'];
                    $homeConceded = $team[$game->getHomeTeam()]['conceded'];
                    $awayScored = $team[$game->getAwayTeam()]['scored'];
                    $awayConceded = $team[$game->getAwayTeam()]['conceded'];

                    $team[$game->getHomeTeam()]['scored'] = $homeScored + $game->getHomeScore();
                    $team[$game->getHomeTeam()]['conceded'] = $homeConceded + $game->getAwayScore();
                    $team[$game->getAwayTeam()]['scored'] = $awayScored + $game->getAwayScore();
                    $team[$game->getAwayTeam()]['conceded'] = $awayConceded + $game->getHomeScore();
                }
            }
        } else {
            throw new CustomException ("No game objects initialised");    
        }
        
        return $team;
    }
}

?>