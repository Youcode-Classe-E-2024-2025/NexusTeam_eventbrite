<?php
namespace App\Models;

use App\core\Model;

class Organizer extends Model
{
    public function stat()
    {
        $this->table = "stats";
        print_r($this->findAll());
        echo 'hhhhhhhhhhhhhhhhhhhhhhhh';
    }
}