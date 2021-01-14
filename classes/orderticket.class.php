<?php

//This class is used to fill out XML order "tickets".
//The tickets are templates that are filled out with
//the required order parameters.
//Using the tickets keeps the XML identical (other than opening
//tags and previewId) between the preview and place order process.

class OrderTicket
{
    function __construct($root_dir)
    {
        $this->root_dir = $root_dir;
        $this->file_name = "";
        $this->ticket_data = "";
        $this->ticket_clone = "";
    }
   
    //Load File and Set Root
    function LoadOptionOrderTicket($ticket_type)
    {
        if($ticket_type == "single")
        {
            $this->file_name = "$this->root_dir/singleoption.xml";
        }
        elseif($ticket_type == "double")
        {
            $this->file_name = "$this->root_dir/doubleoption.xml";
        }
        else 
        {
            $message = "Ticket Load: $ticket_type Does Not Exist";
            $this->TicketError("$message");
        }
        if(file_exists($this->file_name)) 
        {
            $fd = fopen($this->file_name,"r");
            $this->ticket_data = fread($fd, filesize($this->file_name));
            fclose($fd);
        }
        else 
        {
            $message = "Ticket Load: " . $this->file_name . " Does Not Exist";
            $this->TicketError("$message");
        }
    }
    
    function Parse($key, $value)
    {
        $key = '/{' . "$key" . '}/';
        $this->ticket_data = preg_replace("$key","$value",$this->ticket_data);
    }
    
    function Clone()
    {
        $this->ticket_clone = "$this->ticket_data";
    }
    
    //Raise Error Function
    function TicketError($message)
    {
            trigger_error($message);
            exit;
    }
}
?>
