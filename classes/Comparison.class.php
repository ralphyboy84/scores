<?php

class Comparison
{
    private $userScoreObjects;
    
    public function setUserScoreObjects($key = false, $obj)
    {
        $this->userScoreObjects[$key] = $obj;   
    }
    
    public function getUserScoreObjects($key = false)
    {
        if ($key) {
            return $this->userScoreObjects[$key];   
        } else {
            return $this->userScoreObjects;   
        }
    }
    
    public function showComparison()
    {     
        $objects = $this->getUserScoreObjects();
        
        if ($objects) {
            $keys = array_keys($objects);
            
            foreach ($keys as $user) {
                $gameObjects = $objects[$user]->getGameObjects();
                
                $x=0;
                
                if ($gameObjects) {
                    foreach ($gameObjects as $gameObject) {
                        $array[$x]['match'] = $gameObject->getHomeTeam()." vs ".$gameObject->getAwayTeam();
                        
                        $predictionObject = $objects[$user]->getPredictionObjects($gameObject->getGameId());
                        
                        $array[$x][$user] = $predictionObject->getPredictedHomeScore()." - ".$predictionObject->getPredictedAwayScore();
                        $x++;
                    }
                }
            }
        }
        
        if ($array) {
            foreach ($array as $row) {
                foreach ($row as $col) {
                    $cols[] = "<td>$col</td>";   
                }
                $rows[] = "<tr>".implode($cols)."</tr>";
                unset($cols);
            }
            
            return "<table>".implode($rows)."</table>";
        }
    }
}

?>