<?php

namespace App\Models;

use Core\Model;

class Ticket extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = "ticket";
    }

    public function getTickets()
    {
        $tickets = $this->db->query("SELECT * FROM $this->table")->results();
        $results = [];

        foreach ($tickets as $ticket) {
            $ticket_type = $this->db->get("ticket_type", ['id', '=', $ticket->type])->first();
            $ticket->type = $ticket_type;
            array_push($results, $ticket);
        }
        return $results;
    }

    public function createTicket($data)
    {
        $fields = array('event' => $data['event'], 'type' => $data['type'], 'created_at' => $data['created_at'] );
        if ($this->db->insert($this->table, $fields)) {
            return true;
        } else {
            return false;
        }
    }

    public function editTicket($data)
    {
        if (isset($data->event) && isset($data->type)) {
            if ($this->db->update($this->table, $data->id, ["event" => $data->event, "type" => $data->type])) {
                return true;
            } else {
                return false;
            }
            
        } elseif (isset($data->event) && !isset($data->type)) {
            if ($this->db->update($this->table, $data->id, ["event" => $data->event])) {
                return true;
            } else {
                return false;
            }
        } elseif (isset($data->type) && !isset($data->event)) {
            if ($this->db->update($this->table, $data->id, ["type" => $data->type])) {
                return true;
            } else {
                return false;
            }
        }
    }
}