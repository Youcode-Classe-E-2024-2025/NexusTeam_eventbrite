<?php

namespace App\Models;
use App\Core\Database;
use DateTime;
use Exception;

class Event
{
    private int $id;
    private string $title;
    private string $description;
    private DateTime $start_date;
    private DateTime $end_date;
    private string $location;
    private float $price;
    private int $max_capacity;
    private int $organizer_id;
    private string $state;
    private ?string $promotional_image;
    private int $category_id;

    private Database $pdo;
    public function __construct()
    {
        $this->pdo = new Database();
        $this->id = 0;
        $this->title = "";
        $this->description = "";
        $this->start_date = new DateTime();
        $this->end_date = new DateTime();
        $this->location = "";
        $this->price = 0.0;
        $this->max_capacity = 0;
        $this->organizer_id = 1;
        $this->state = 'pending';
        $this->promotional_image = null;
        $this->category_id = 1;
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
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

    public function getStartDate(): DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(string $start_date): Event
    {
        $this->start_date = new DateTime($start_date);
        return $this;
    }

    public function getEndDate(): DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(string $end_date): Event
    {
        $this->end_date = new DateTime($end_date);
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): Event
    {
        $this->price = $price;
        return $this;
    }

    public function getMaxCapacity(): int
    {
        return $this->max_capacity;
    }

    public function setMaxCapacity(int $max_capacity): Event
    {
        $this->max_capacity = $max_capacity;
        return $this;
    }

    public function getOrganizerId(): int
    {
        return $this->organizer_id;
    }

    public function setOrganizerId(int $organizer_id): Event
    {
        $this->organizer_id = $organizer_id;
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): Event
    {
        $this->state = $state;
        return $this;
    }

    public function getPromotionalImage(): ?string
    {
        return $this->promotional_image;
    }

    public function setPromotionalImage(?string $promotional_image): Event
    {
        $this->promotional_image = $promotional_image;
        return $this;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): Event
    {
        $this->category_id = $category_id;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }


    public function getAll(): array
    {
        $sql = "SELECT * FROM events";
        return $this->pdo->fetchAll($sql) ?? [];
    }

    public function getById(): self
    {
        $sql = "SELECT * FROM events WHERE id = :id";
        $result = $this->pdo->fetch($sql, [":id" => $this->id]);

        $event = new Event();
        $event->fill($result);
        $event->setMaxCapacity($result['max_capacity']);
        $event->setPromotionalImage($result['promotional_image']);
        $event->setStartDate($result['start_date']);
        $event->setEndDate($result['end_date']);
        return $event;
    }

    public function create(): bool
    {
        $sql = "INSERT INTO events (
                title, description, start_date, end_date, location, price, max_capacity, organizer_id, state, promotional_image, category_id            
        ) VALUES (
                  :title, :description, :start_date, :end_date, :location, :price, :max_capacity, :organizer_id, :state, :promotional_image, :category_id
        )";

        $params = [
            ":title" => $this->title,
            ":description" => $this->description,
            ":start_date" => $this->start_date->format('Y-m-d'),
            ":end_date" => $this->end_date->format('Y-m-d'),
            ":location" => $this->location,
            ":price" => $this->price,
            ":max_capacity" => $this->max_capacity,
            ":organizer_id" => $this->organizer_id,
            ":state" => $this->state,
            ":promotional_image" => $this->promotional_image,
            ":category_id" => $this->category_id,
        ];
        return (bool)$this->pdo->execute($sql, $params);
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM events WHERE id = :id";
        return (bool)$this->pdo->execute($sql, [":id" => $this->id]);
    }
}
