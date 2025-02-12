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

    public function getAll(): array {
        $sql = "SELECT * FROM tags";
        return $this->db->fetchAll($sql) ?? [];
    }

    public function getById(): Tag {
        $sql = "SELECT * FROM tags WHERE id = :id";
        $result = $this->db->fetch($sql, [":id" => $this->id]);

        return new Tag($result['id'], $result['name']);
    }

    public function delete(): bool {
        $sql = "DELETE FROM tags WHERE id = :id";
        return $this->db->execute($sql, [":id" => $this->id]);
    }

    public function update(): bool {
        $sql = "UPDATE tags SET name = :name WHERE id = :id";
        return $this->db->execute($sql, [":name" => $this->name, ":id" => $this->id]);
    }

}