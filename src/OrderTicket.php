<?php
namespace phpetrade;

//Class handles all of the OAuth signing, header creation, and HTTP communication.
//Assumes XML body response (default for ETrade API v1).

class OrderTicket
{
    use EndPointTrait;
    protected $config;

    public function __construct($root_dir, Config $config)
    {
        $this->root_dir = $root_dir;
        $this->file_name = "";
        $this->ticket_data = "";
        $this->ticket_clone = "";
        $this->config = $config;
    }
   
    //Load File and Set Root
    public function LoadOptionOrderTicket($ticket_type)
    {
        if($ticket_type == "single")
        {
            $this->file_name = "$this->root_dir/singleoption.xml";
        }
        elseif($ticket_type == "double")
        {
            $this->file_name = "$this->root_dir/doubleoption.xml";
        }
        elseif($ticket_type == "triple")
        {
            $this->file_name = "$this->root_dir/tripleoption.xml";
        }
        elseif($ticket_type == "quad")
        {
            $this->file_name = "$this->root_dir/quadoption.xml";
        }
        elseif($ticket_type == "buywrite")
        {
            $this->file_name = "$this->root_dir/buywrite.xml";
        }
        else 
        {
            $message = "Ticket Load: $ticket_file Does Not Exist";
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
    
    public function Parse($key, $value)
    {
        $key = '/{' . "$key" . '}/';
        $this->ticket_data = preg_replace("$key","$value",$this->ticket_data);
    }
    
    public function Clone()
    {
        $this->ticket_clone = "$this->ticket_data";
    }
    
    //Raise Error Function
    public function TicketError($message)
    {
            trigger_error($message);
            exit;
    }
}