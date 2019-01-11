<?php

namespace App\Models;

use Core\Model;

class TicketType extends Model
{
    public function __construct()
    {
        $this->table = "ticket_type";
    }

    public function createTicketType($data)
    {
        if ($this->db->insert($this->table, ['name' => $data['name']])) {
            return true;
        } else {
            return false;
        }
    }

    public function editTicketType($data)
    {
        if ($this->db->update($this->table, $data->id, ["name" => $data->name])) {
            return true;
        } else {
            return false;
        }
    }
}