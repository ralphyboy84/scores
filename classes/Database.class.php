<?php

require_once("MasterDB.class.php");

class Database extends MasterDB
{
    private $connection;
        
    public function getDatabaseConnection()
    {   
        mysqli_report(MYSQLI_REPORT_STRICT);
        
        if (!$this->connection) {
            try {
                if ($_SERVER['HTTP_HOST'] == "localhost") {
                    $conn = new mysqli('localhost',"root","","scores");
                } else {
                    $conn = new mysqli('localhost',"focususer","ju5td01t","scores");  
                }
            } catch(Exception $e) {
                echo "Error connecting to database";
                exit;
            }

            if($conn->connect_error) {
                throw new CustomException ("Cannot connect to database: ".$conn->connect_error);
            } else {
                $this->connection = $conn;
            }
        } 
        
        return $this->connection;
    }
}

?>