<?php

require_once("MasterDB.class.php");

class ScopeDatabase extends MasterDB
{
    private $connection;
        
    public function getDatabaseConnection()
    {
        $SCOPEDBUSER = "ifaceuserlssi";
        $SCOPEMYSQLPSSWD = "YT3zx7HnLX";
        $SCOPESERVER = "scopereportsdb.spnet.local";

        $SCOPELIVEDB = "scope";

        if (!$this->connection) {
            $conn = new mysqli($SCOPESERVER, $SCOPEDBUSER, $SCOPEMYSQLPSSWD, "scope");

            if($conn === false) {

            } else {
                $this->connection = $conn;
            }
        } 
        
        return $this->connection;
    }
}

?>