<?php

class User
{
    private $user;
    private $pointsTotal;
    private $dbObject;    
    private $scopeDbObject;
    private $username;
    private $password;
    private $oldPosition;
    private $newPosition;
    
    function __construct($user = false, $dbObject = false)
    {
        if ($user && $dbObject) {
            $this->setDbObject($dbObject);
            $this->setUser($user);
            
            $sql = "SELECT * FROM users WHERE id = '$user'";
            $res = $dbObject->performSelectQuery($sql);
            
            if ($res) {
                $this->setUsername($res[0]['name']);   
            }
        }
    }
    
    public function setUser($val)
    {
        $this->user = $val;   
    }
    
    public function getUser()
    {
        return $this->user;   
    }
    
    public function setPointsTotal($val)
    {
        $this->pointsTotal = $val;   
    }
    
    public function getPointsTotal()
    {
        return $this->pointsTotal;   
    }
    
    public function setDbObject($obj)
    {
        $this->dbObject = $obj;   
    }
    
    public function getDbObject()
    {
        return $this->dbObject;   
    }
    
    public function setScopeDbObject($obj)
    {
        $this->scopeDbObject = $obj;   
    }
    
    public function getScopeDbObject()
    {
        return $this->scopeDbObject;   
    }
    
    public function setUsername($val)
    {
        $this->username = $val;   
    }
    
    public function getUsername()
    {
        return $this->username;   
    }
    
    public function setPassword($val)
    {
        $this->password = $val;   
    }
    
    public function getPassword()
    {
        return $this->password;   
    }
    
    public function setOldPosition($val)
    {
        $this->oldPosition = $val;   
    }
    
    public function getOldPosition()
    {
        return $this->oldPosition;   
    }
    
    public function setNewPosition($val)
    {
        $this->newPosition = $val;   
    }
    
    public function getNewPosition()
    {
        return $this->newPosition;   
    }
    
    public function calculatePointsTotal()
    {
        $dbObject = $this->getDbObject();
        
        $sql = "SELECT points FROM userguess WHERE userid = '".$this->getuser()."' ";
        $results = $dbObject->performSelectQuery($sql);
        
        if ($results) {
            foreach ($results as $game) {
                $points = $this->getPointsTotal();
                
                if (!is_null($game['points'])) {
                    $this->setPointsTotal($points + $game['points']);
                }
            }
        }
    }
    
    public function getScopeUserName()
    {
        $dbObject = $this->getScopeDbObject();
        
        $sql = "SELECT * FROM master WHERE psosno = '".$this->getuser()."' ";
        $results = $dbObject->performSelectQuery($sql);
        return $results[0]['forename']." ".$results[0]['surname'];
    }
    
    public function getLoginRegisterScreen()
    {
        $login = $this->loginUserScreen();
        
        return<<<EOHTML
$login
EOHTML;
    }
    
    public function registerUserScreen()
    {
        return<<<EOHTML
        
EOHTML;
    }
    
    public function loginUserScreen()
    {
        return<<<EOHTML
        <form id='loginForm' method='post'>
            <div class="w3-row">
                <div class="w3-col m6 l6">
                <p>Name</p>
                </div>
                <div class="w3-col m6 l6">
                <p><input type='input' id='name' name='name' value='' /></p>
                </div>
            </div>
            <div class="w3-row">
                <div class="w3-col m6 l6">
                <p>Password</p>
                </div>
                <div class="w3-col m6 l6">
                <p><input type='password' id='password' name='password' value='' /></p>
                </div>
            </div>
            <div class="w3-row">
                <div class="w3-col">
                <p><button type='button' id='login'>Login</button></p>
                </div>
            </div>
        </form>
        <div id='debuglogin'></div>
EOHTML;
    }
    
    public function loginUser()
    {
        $username = $this->getUsername();
        $password = $this->getPassword();
        $dbObject = $this->getDbObject();
        
        $sql = "SELECT * FROM users WHERE name = '".$dbObject->quote($username)."'";
        $res = $dbObject->performSelectQuery($sql);
        
        if ($res[0]['password'] == $password) {
            $_SESSION['username'] = $username;
            $_SESSION['usercookie'] = $res[0]['id'];
            return "OK";
        } else {
            return "NOTOK";   
        }
    }
}

?>