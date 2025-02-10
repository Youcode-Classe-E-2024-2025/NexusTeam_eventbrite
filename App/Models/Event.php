<?php
namespace App\Models;

use App\Core\Database;
use DateTime;

Enum Status: string {
    case PENDING = "pending";
    case APPROVED = "approved";
    case REJECTED = "rejected";
}

class Event {

    private int $id;
    private string $title;
    private string $description;
    private DateTime $date;
    private string $location;
    private int $price;
    private int $capacity;
    private Status $status;
    private bool $is_sponsored;

    private readonly Database $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance();
        $this->id = 0;
        $this->title = "";
        $this->description = "";
        $this->date = new DateTime();
        $this->location = "";
        $this->price = 0;
        $this->capacity = 0;
        $this->status = Status::PENDING;
        $this->is_sponsored = false;
    }


    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            } else {
                $this->$key = $value;
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

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): Event
    {
        $this->date = $date;
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

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): Event
    {
        $this->status = $status;
        return $this;
    }

    public function isIsSponsored(): bool
    {
        return $this->is_sponsored;
    }

    public function setIsSponsored(bool $is_sponsored): Event
    {
        $this->is_sponsored = $is_sponsored;
        return $this;
    }


    /**
     * @throws \Exception
     */
    public function getAll(): array{
        $sql = "SELECT * FROM events";
        return $this->pdo->fetchAll($sql);
    }


}