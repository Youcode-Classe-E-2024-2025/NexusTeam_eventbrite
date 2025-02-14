<?php

namespace App\Models;

use App\Core\Database;

class EventTag
{

    private Event $event;
    private Tag $tag;

    private Database $db;


    public function __construct()
    {
        $this->event = new Event();
        $this->tag = new Tag();
        $this->db = Database::getInstance();
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): EventTag
    {
        $this->event = $event;
        return $this;
    }

    public function getTag(): Tag
    {
        return $this->tag;
    }

    public function setTag(Tag $tag): EventTag
    {
        $this->tag = $tag;
        return $this;
    }

    public function save(): bool
    {
        $sql = "INSERT INTO events_tags (event_id, tag_id) VALUES (:event_id, :tag_id)";
        return $this->db->execute($sql, [':event_id' => $this->event->getId(), ':tag_id' => $this->tag->getId()]);
    }

    public function getTagsByEvent(): array
    {
        $sql = "SELECT * FROM  tags WHERE id IN (SELECT tag_id FROM events_tags WHERE event_id = :event_id)";
        $result = $this->db->fetchAll($sql, [':event_id' => $this->event->getId()]);

        $tags = [];

        foreach ($result as $tag) {
            $new = new Tag($tag['id'], $tag['name']);

            $tags[] = $new;
        }
        return $tags;
    }

    public function deleteTagsByEvent(): bool
    {
        $sql = "DELETE FROM events_tags WHERE event_id = :event_id";
        return $this->db->execute($sql, [':event_id' => $this->event->getId()]);
    }

}