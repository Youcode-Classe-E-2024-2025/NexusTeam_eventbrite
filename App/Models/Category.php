<?php

namespace App\Models;

use App\Core\Database;

class Category
{

    private int $id;
    private string $name;
    private Database $db;

    public function __construct(){
        $this->db = Database::getInstance();
        $this->id = 0;
        $this->name = '';
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

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function getId(): int{
        return $this->id;
    }

    public function setName(string $name): void{
        $this->name = $name;
    }

    public function getName(): string{
        return $this->name;
    }

    public function save(): bool {
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        return (bool) $this->db->execute($sql);
    }

    public function getAll(): array {
        $sql = "SELECT * FROM categories";
        return $this->db->fetchAll($sql) ?? [];
    }

    public function getById(){
        $sql = "SELECT * FROM categories WHERE id = :id";
        $result = $this->db->fetch($sql);

        $category = new Category();
        $category->fill($result);
        return $category;
    }

}