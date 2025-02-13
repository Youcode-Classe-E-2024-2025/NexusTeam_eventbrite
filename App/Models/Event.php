<?php

namespace App\Models;
use App\Core\Database;
use DateTime;

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
    private Category $category;

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
        $this->category = new Category();
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if ($key === 'id') {
                continue;
            }

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

    public function getCategory(): category
    {
        return $this->category;
    }

    public function setCategory(Category $category): Event
    {
        $this->category = $category;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }


    public function getAll(): array
    {
        $sql = "SELECT * FROM events";
        $results = $this->pdo->fetchAll($sql);

        $events = [];

        foreach ($results as $result) {
            $event = new Event();
            $event->fill($result);
            $event->setId($result['id']);
            $event->setMaxCapacity($result['max_capacity']);
            $event->setPromotionalImage($result['promotional_image']);
            $event->setStartDate($result['start_date']);
            $event->setEndDate($result['end_date']);
            $event->setCategory((new Category())->setId($result['category_id'])->getById());
            $events[] = $event;
        }

        return $events;
    }

    public function getById(): self
    {
        $sql = "SELECT * FROM events WHERE id = :id";
        $result = $this->pdo->fetch($sql, [":id" => $this->id]);

        $event = new Event();
        $event->fill($result);
        $event->setId($result['id']);
        $event->setMaxCapacity($result['max_capacity']);
        $event->setPromotionalImage($result['promotional_image']);
        $event->setStartDate($result['start_date']);
        $event->setEndDate($result['end_date']);
        $event->setCategory((new Category())->setId($result['category_id'])->getById());
        return $event;
    }

    public function create(): bool
    {
        $sql = "INSERT INTO events (
                title, description, start_date, end_date, location, price, max_capacity, organizer_id, state, promotional_image, category_id            
        ) VALUES (
                  :title, :description, :start_date, :end_date, :location, :price, :max_capacity, :organizer_id, :state, :promotional_image, :category_id
        ) RETURNING id";

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
            ":category_id" => $this->category->getId(),
        ];
        $stmt = $this->pdo->getConnection()->prepare($sql);
        if ($stmt->execute($params)) {
            $this->id = $stmt->fetchColumn();
            return true;
        }
        return false;
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM events WHERE id = :id";
        return (bool)$this->pdo->execute($sql, [":id" => $this->id]);
    }

    public function update(): bool
    {
        $sql = "UPDATE events SET title = :title, description = :description, start_date = :start_date, end_date = :end_date, 
                  location = :location, price = :price, max_capacity = :max_capacity,
                  state = :state, promotional_image = :promotional_image, category_id = :category_id WHERE id = :id";
        $params = [
            ":title" => $this->title,
            ":description" => $this->description,
            ":start_date" => $this->start_date->format('Y-m-d'),
            ":end_date" => $this->end_date->format('Y-m-d'),
            ":location" => $this->location,
            ":price" => $this->price,
            ":max_capacity" => $this->max_capacity,
            ":state" => $this->state,
            ":promotional_image" => $this->promotional_image,
            ":category_id" => $this->category->getId(),
            ":id" => $this->id
        ];

        return (bool)$this->pdo->execute($sql, $params);
    }

    public static function search(string $searchTerm = '', int $page = 1, int $limit = 8): array
    {
        $offset = ($page - 1) * $limit;

        $countQuery = "SELECT COUNT(DISTINCT e.id) as total 
                   FROM events e
                   JOIN categories c ON e.category_id = c.id
                   LEFT JOIN events_tags et ON e.id = et.event_id
                   LEFT JOIN tags t ON et.tag_id = t.id
                   WHERE e.title ILIKE :searchTerm 
                      OR e.description ILIKE :searchTerm 
                      OR e.location ILIKE :searchTerm 
                      OR t.name ILIKE :searchTerm";

        $dataQuery = "
                        SELECT 
                            e.id,
                            e.title,
                            e.description,
                            e.start_date,
                            e.end_date,
                            e.location,
                            e.price,
                            e.max_capacity,
                            e.organizer_id,
                            e.state,
                            e.promotional_image,
                            c.name AS category_name,
                            e.created_at,
                            COALESCE(ARRAY_AGG(t.name) FILTER (WHERE t.id IS NOT NULL), '{}') AS tags
                        FROM events e
                        JOIN categories c ON e.category_id = c.id
                        LEFT JOIN events_tags et ON e.id = et.event_id
                        LEFT JOIN tags t ON et.tag_id = t.id
                        WHERE e.title ILIKE :searchTerm 
                           OR e.description ILIKE :searchTerm 
                           OR e.location ILIKE :searchTerm 
                           OR t.name ILIKE :searchTerm
                        GROUP BY e.id, c.name
                        LIMIT :limit OFFSET :offset;
                    ";

        $db = Database::getInstance();

        $searchTerm = '%' . $searchTerm . '%';

        $total = $db->fetch($countQuery, ['searchTerm' => $searchTerm])['total'] ?? 0;

        $pageCount = (int) ceil($total / $limit);

        $events = $db->fetchAll($dataQuery, [
            'searchTerm' => $searchTerm,
            'limit' => $limit,
            'offset' => $offset
        ]);

        return [
            'total' => $total,
            'page_count' => $pageCount,
            'current_page' => $page,
            'events' => $events
        ];
    }
}
