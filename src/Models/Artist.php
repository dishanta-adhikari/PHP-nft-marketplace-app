<?php

namespace App\Models;

use PDO;

class Artist
{
    private $con;

    public function __construct($db)
    {
        $this->con = $db;
    }

}
