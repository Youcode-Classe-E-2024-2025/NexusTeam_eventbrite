<?php
namespace App\Models;

use App\core\Model;

class Organizer extends Model
{
    public function eventList(): array
    {
        $this->primaryKey = "id";
        $this->table = "events";
        $data = $this->find(["organizer_id"=>isset($_SESSION["user_id"])?$_SESSION["user_id"]:2],"ASC",100);
        $this->primaryKey = "id";
        $this->table = "categories";
        foreach ($data as $key => $d) {
            $data[$key]["category_name"] = $this->first(["id"=>$d["category_id"]])["name"];
        }
        return $data;
    }
    public function eventStats($id): array
    {
        $this->primaryKey = "event_id";
        $this->table = "event_stats";
        $data = $this->first(["event_id"=>$id]);
        $this->primaryKey = "id";
        $this->table = "events";
        $data += $this->first(["id"=>$id]);
        return $data;
    }
}