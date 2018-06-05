<?php

class MasterDB
{
    public function performSelectQuery($sql)
    {
        $rows = array();
        
        $conn = $this->getDatabaseConnection();

        if (!$conn->connect_error) {
            $result = $conn->query($sql);
            
            if ($result === false) {
                return false;   
            }
            
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;   
            }
        } else {
            throw new CustomException ("Cannot connect to database: ".$conn->connect_error);   
        }
        
        return $rows;
    }
    
    public function perfomInsertQuery($args, $tableName)
    {
        $conn = $this->getDatabaseConnection();
        
        if ($args) {
            foreach ($args as $field => $value) {
                $sqlArgs[] = "`$field` = '".$this->quote($value)."'";   
            }
            
            $sql = "INSERT INTO $tableName SET ".implode($sqlArgs, " , ");
            $result = $conn->query($sql);
        } else {
            throw new CustomException("No arguments present for insert query");   
        }
    }
    
    public function performUpdateQuery($sql)
    {
        $conn = $this->getDatabaseConnection();
        
        if (!$conn->connect_error) {
            $result = $conn->query($sql);
        } else {
            throw new CustomException ("Cannot connect to database: ".$conn->connect_error);   
        }
    }
    
    public function quote($value) 
    {
        $conn = $this->getDatabaseConnection();
        return $conn->real_escape_string($value);
    }   
}

?>