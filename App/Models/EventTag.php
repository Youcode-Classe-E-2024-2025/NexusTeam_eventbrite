<?php

namespace App\Models;

class EventTag
{

    private Event $event;
    private Tag $tag;


    public function __construct()
    {
        $this->event = new Event();
        $this->tag = new Tag();
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

}