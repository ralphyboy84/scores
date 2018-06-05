<?php
class DateFormat
{
	private $date;
		
    public function setDate($val)
    {
        $this->date = $val;   
    }
	
	public function getDate()
    {
        return $this->date;   
    }
    
	public function formatDateFromDatabase ()
	{
		$args = explode ( "-" , $this->getDate() );
		return $args[2]."/".$args[1]."/".$args[0];
	}	

	public function formatDateToDatabase ()
	{
		$args = explode ( "/" , $this->getDate() );
		return $args[2]."-".$args[1]."-".$args[0];
	}
}

?>
