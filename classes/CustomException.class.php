<?php

class CustomException extends Exception 
{
    public function errorMessage() {
        //error message
        $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile().':&nbsp;&nbsp;<b>'.$this->getMessage()."</b>";
        return $errorMsg;
    }
}

?>