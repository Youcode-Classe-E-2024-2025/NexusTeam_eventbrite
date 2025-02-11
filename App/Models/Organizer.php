<?php
namespace App\Models;

use App\core\Model;

class Organizer extends Model
{
    public function stat(): array
    {
        $this->primaryKey = "event_id";
        $this->table = "event_stats";
        return $this->findAll();
    }
}