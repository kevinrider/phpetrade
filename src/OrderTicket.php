<?php

namespace phpetrade;

/*
 * Builds an option order ticket from a pre-defined xml order file
 */

class OrderTicket
{
    use EndPointTrait;

    public function __construct(
        public string $root_dir,
        protected Config $config,
        public string $file_name = "",
        public string $ticket_data = "",
        public string $ticket_clone = ""
    ) {
    }

    /**
     * @param $ticket_type
     * @return void
     * Load File and Set Root
     */
    public function LoadOptionOrderTicket($ticket_type): void
    {
        if ($ticket_type == "single") {
            $this->file_name = "$this->root_dir/singleoption.xml";
        } elseif ($ticket_type == "double") {
            $this->file_name = "$this->root_dir/doubleoption.xml";
        } elseif ($ticket_type == "triple") {
            $this->file_name = "$this->root_dir/tripleoption.xml";
        } elseif ($ticket_type == "quad") {
            $this->file_name = "$this->root_dir/quadoption.xml";
        } elseif ($ticket_type == "buywrite") {
            $this->file_name = "$this->root_dir/buywrite.xml";
        } else {
            $this->TicketError("Ticket Load: $ticket_type Does Not Exist");
        }
        if (file_exists($this->file_name)) {
            $fd = fopen($this->file_name, "r");
            $this->ticket_data = fread($fd, filesize($this->file_name));
            fclose($fd);
        } else {
            $this->TicketError("Ticket Load: " . $this->file_name . " Does Not Exist");
        }
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function Parse($key, $value): void
    {
        $key = '/{' . "$key" . '}/';
        $this->ticket_data = preg_replace("$key", "$value", $this->ticket_data);
    }

    /**
     * @return void
     */
    public function Clone(): void
    {
        $this->ticket_clone = "$this->ticket_data";
    }

    /**
     * @param $message
     * @return void
     * Raise Error Function
     */
    public function TicketError($message): void
    {
        trigger_error($message);
        exit;
    }
}
