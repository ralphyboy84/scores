<?php
require_once("User.class.php");
require_once("ScopeDatabase.class.php");

class Leaderboard
{
    private $dbObject;
    private $userObjects;
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }   
    
    public function setUserObjects($key, $obj)
    {
        $this->userObjects[$key] = $obj;   
    }
    
    public function getUserObjects($key = false)
    {
        if ($key) {
            return $this->userObjects[$key];   
        } else {
            return $this->userObjects;   
        }
    }
    
    public function generateLeaderboard()
    {
        $dbObject = $this->getDbObject();
        
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            $sql = "SELECT SUM(points) as points, userid, name FROM userguess, users WHERE userguess.userid = users.id GROUP BY userid ORDER BY SUM(points) DESC";
        } else {
            $sql = "SELECT SUM(points) as points, userid FROM userguess GROUP BY userid ORDER BY SUM(points) DESC";   
        }
        
        $results = $dbObject->performSelectQuery($sql);
        
        if ($results) {
            foreach ($results as $userInfo) {
                $user = new User();
                $user->setUser($userInfo['userid']);
                $user->setUsername($userInfo['name']);
                $user->setScopeDbObject(new ScopeDatabase());
                $user->setPointsTotal($userInfo['points']); 
                
                $sql = "SELECT position FROM history WHERE userid = '".$userInfo['userid']."' ORDER BY runid DESC ";
                $historyResults = $dbObject->performSelectQuery($sql);
                
                $user->setNewPosition($historyResults[0]['position']);
                $user->setOldPosition($historyResults[1]['position']);
                
                $this->setUserObjects($userInfo['userid'] , $user);
            }
        } else {
            throw new CustomException ("No results from the database");   
        }
    }
    
    public function displayLeaderboardAsTable()
    {
        $displayArray = array();
        $users = $this->getUserObjects();
        
        if ($users) {
            $x=1;
            foreach ($users as $userObj) {
                
                if ($_SERVER['HTTP_HOST'] == "localhost") {
                    $userName = $userObj->getUsername();
                } else {
                    $userName = $userObj->getScopeUserName();   
                }
                
                if ($userObj->getPointsTotal()) {
                    if ($userObj->getOldPosition() < $userObj->getNewPosition()) {
                        $diff = $userObj->getNewPosition() - $userObj->getOldPosition();
                        $movement = "<i class='fa fa-caret-down'></i> ".$diff;   
                    } else if ($userObj->getOldPosition() > $userObj->getNewPosition()) {
                        $diff = $userObj->getOldPosition() - $userObj->getNewPosition();
                        $movement = "<i class='fa fa-caret-up'></i> ".$diff;   
                    } else if ($userObj->getOldPosition() == $userObj->getNewPosition()) {
                        $movement = "=";   
                    }
                    
                    $displayArray[] = "<tr class='userRow' id='".$userObj->getUser()."'><td>".$x."</td><td>".$userName."</td><td>".$userObj->getPointsTotal()."</td></tr>";   
                    $x++;
                }
            }
        } else {
            throw new CustomException ("No user objects created for leaderboard");   
        }
        
        if ($displayArray) {
            return "<table><thead><tr><th>Position</th><th>Person</th><th>Score</th><th>&nbsp;</th></tr></thead><tbody>".implode($displayArray)."</tbody></table>";
        } else {
            return "The games have not started yet so there is no leaderboard to display!";   
        }
    }
}

?>