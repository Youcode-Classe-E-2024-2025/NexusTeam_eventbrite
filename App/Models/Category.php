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

    public function setId(int $id): Category{
        $this->id = $id;
        return $this;
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
        $result = $this->db->fetchAll($sql);

        $categories = [];

        foreach ($result as $category) {
            $categoryObj = new Category();
            $categoryObj->fill($category);
            $categories[] = $categoryObj;
        }

        return $categories;
    }

    public function getById(): Category
    {
        $sql = "SELECT * FROM categories WHERE id = :id";
        $result = $this->db->fetch($sql, [":id" => $this->id]);

        $category = new Category();
        $category->fill($result);
        return $category;
    }

    public function create(): bool {
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        return (bool) $this->db->execute($sql, [":name" => $this->name]);
    }


    public function update(): bool {
        $sql = "UPDATE categories SET name = :name WHERE id = :id";
        return (bool) $this->db->execute($sql, [":name" => $this->name, ":id" => $this->id]);
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM categories WHERE id = :id";
        return (bool) $this->db->execute($sql, [":id" => $this->id]);
    }

}