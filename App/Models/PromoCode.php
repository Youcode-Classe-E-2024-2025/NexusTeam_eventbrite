<?php

namespace App\Models;

use App\Core\Database;

class PromoCode
{
    private int $id;
    private string $code;
    private float $discount;
    private string $expiration;
    private ?int $event_id;
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->id = 0;
        $this->code = '';
        $this->discount = 0.0;
        $this->expiration = '';
        $this->event_id = null;
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

    // Getter & Setter Methods
    public function setId(?int $id): PromoCode
    {
        if ($id!==null) {
            $this->id = $id;
            return $this;
        }
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setCode(string $code): PromoCode
    {
        $this->code = $code;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setDiscount(float $discount): PromoCode
    {
        $this->discount = $discount;
        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setExpiration(string $expiration): PromoCode
    {
        $this->expiration = $expiration;
        return $this;
    }

    public function getExpiration(): string
    {
        return $this->expiration;
    }

    public function setEventId(?int $event_id): PromoCode
    {
        $this->event_id = $event_id;
        return $this;
    }

    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    // CRUD Operations
    public function create(): bool
    {
        $sql = "INSERT INTO promo_codes (code, discount, expiration, event_id) VALUES (:code, :discount, :expiration, :event_id)";
        $success = (bool) $this->db->execute($sql, [
            ":code" => $this->code,
            ":discount" => $this->discount,
            ":expiration" => $this->expiration,
            ":event_id" => $this->event_id
        ]);
        if ($success) {
            $this->setId($this->db->lastInsertId());
        }
    
        return $success;
    }

    public function update(): bool
    {
        $sql = "UPDATE promo_codes SET code = :code, discount = :discount, expiration = :expiration, event_id = :event_id WHERE id = :id";
        return (bool) $this->db->execute($sql, [
            ":id" => $this->id,
            ":code" => $this->code,
            ":discount" => $this->discount,
            ":expiration" => $this->expiration,
            ":event_id" => $this->event_id
        ]);
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM promo_codes WHERE id = :id";
        return (bool) $this->db->execute($sql, [":id" => $this->id]);
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM promo_codes";
        $result = $this->db->fetchAll($sql);

        $promoCodes = [];
        foreach ($result as $promo) {
            $promoObj = new PromoCode();
            $promoObj->fill($promo);
            $promoCodes[] = $promoObj;
        }

        return $promoCodes;
    }

    public function getById(): ?PromoCode
    {
        $sql = "SELECT * FROM promo_codes WHERE id = :id";
        $result = $this->db->fetch($sql, [":id" => $this->id]);

        if (!$result) {
            return null;
        }

        $promo = new PromoCode();
        $promo->fill($result);
        return $promo;
    }

    // Retrieve all tickets associated with this promo code
    public function getTickets(): array
    {
        $sql = "SELECT t.* FROM tickets t
                JOIN tickets_promo tp ON t.id = tp.ticket_id
                WHERE tp.promo_code_id = :promo_code_id";

        return $this->db->fetchAll($sql, [":promo_code_id" => $this->id]);
    }
}
