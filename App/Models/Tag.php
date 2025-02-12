<?php

namespace App\Models;

use App\Core\Database;

class Tag
{

    private int $id;
    private string $name;

    private Database $db;

    public function __construct(int $id = 0, string $name = '') {
        $this->id = $id;
        $this->name = $name;
        $this->db = Database::getInstance();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function save(): bool {
        $sql = "INSERT INTO tags (name) VALUES (:name)";
        return $this->db->execute($sql, [':name' => $this->name]);
    }

}