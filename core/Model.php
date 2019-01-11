<?php

namespace Core;

use Core\Database as Database;

class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}