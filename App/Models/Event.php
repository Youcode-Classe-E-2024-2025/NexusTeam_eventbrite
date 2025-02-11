<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use DateTime;

class Event extends Model
{

    private int $id;
    private string $title;
    private string $description;
    private DateTime $date;
    private string $location;
    private int $price;
    private int $capacity;
    private string $status;
    private int $is_sponsored = 0;


    public function __construct()
    {
        parent::__construct();
        $this->table = 'events';
        $this->id = 0;
        $this->title = "";
        $this->description = "";
        $this->date = new DateTime();
        $this->location = "";
        $this->price = 0;
        $this->capacity = 0;
        $this->status = 'pending';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Event
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Event
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Event
    {
        $this->description = $description;
        return $this;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(string $date): Event
    {
        $this->date = new DateTime($date);
        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): Event
    {
        $this->location = $location;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): Event
    {
        $this->price = $price;
        return $this;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): Event
    {
        $this->capacity = $capacity;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): Event
    {
        $this->status = $status;
        return $this;
    }

    public function getIsIsSponsored(): bool
    {
        return $this->is_sponsored;
    }

    public function setIsSponsored(string $is_sponsored): Event
    {
        if ($is_sponsored === 'on'){
            $this->is_sponsored = 1;
        } else {
            $this->is_sponsored = 0;
        }

        return $this;
    }


//    /**
//     * @throws \Exception
//     */
//    public function getAll(): array
//    {
//        $sql = "SELECT * FROM events";
//        return $this->pdo->fetchAll($sql) ?? [];
//    }
//
//    public function getById(): self
//    {
//        $sql = "SELECT * FROM events WHERE id = :id";
//        $result = $this->pdo->fetch($sql, [":id" => $this->id]);
//
//        $event = new Event();
//        $event->fill($result);
//        return $event;
//    }
//
//    public function create(): bool
//    {
//        $sql = "INSERT INTO events (title, description, event_date, location, price, capacity, status, is_sponsored)
//        VALUES (:title, :description, :date, :location, :price, :capacity, :status, :is_sponsored)";
//        $params = [":title" => $this->title,
//            ":description" => $this->description,
//            ":date" => $this->date->format('Y-m-d H:i:s'),
//            ":location" => $this->location,
//            ":price" => $this->price,
//            ":capacity" => $this->capacity,
//            ":status" => $this->status,
//            ":is_sponsored" => $this->is_sponsored];
//        return (bool)$this->pdo->execute($sql, $params);
//    }

}