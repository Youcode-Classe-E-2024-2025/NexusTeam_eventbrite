<?php

namespace App\Core;

/**
 * @Model
 * 
 * Provides CRUD operations for database interaction.
 */
class Model
{
    protected string $table = "";
    protected string $primaryKey = "id";
    protected readonly Database $db;

    /**
     * Constructor initializes the database instance.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Fills the model using its setters
     *
     * @param array $data the data to be inserted
     * @return void returns nothing as it just sets the data
     */

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

    /**
     * Inserts a new record into the database.
     * 
     * @param array $data Data to insert.
     * @return bool Returns true on success, false on failure.
     */
    public function insert(array $data): bool
    {
        $keys = array_keys($data);
        $query = "INSERT INTO {$this->table} (" . implode(',', $keys) . ") VALUES (:" . implode(',:', $keys) . ")";
        return $this->db->execute($query, $data) > 0;
    }

    /**
     * Updates an existing record in the database.
     * 
     * @param int $id Record identifier.
     * @param array $data Data to update.
     * @return bool Returns true on success, false on failure.
     */
    public function update(int $id, array $data): bool
    {
        $setClause = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $data[$this->primaryKey] = $id;
        $query = "UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = :{$this->primaryKey}";
        return $this->db->execute($query, $data) > 0;
    }

    /**
     * Deletes a record from the database.
     * 
     * @param int $id Record identifier.
     * @return bool Returns true on success, false on failure.
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->execute($query, ['id' => $id]) > 0;
    }

    /**
     * Retrieves all records with optional ordering and pagination.
     * 
     * @param string $order Sorting order (ASC/DESC).
     * @param int $limit Number of records to fetch.
     * @param int $offset Offset for pagination.
     * @return array Returns an array of records.
     */
    public function findAll(string $order = "DESC", int $limit = 10, int $offset = 0): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} $order LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($query, ['limit' => $limit, 'offset' => $offset]);
    }

    /**
     * Finds records based on given conditions.
     * 
     * @param array $conditions Associative array of column-value pairs.
     * @param string $order Sorting order (ASC/DESC).
     * @param int $limit Number of records to fetch.
     * @return array Returns an array of matching records.
     */
    public function find(array $conditions, string $order = "DESC", int $limit = 10): array
    {
        $whereClause = implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        $query = "SELECT * FROM {$this->table} WHERE $whereClause ORDER BY {$this->primaryKey} $order LIMIT :limit";
        $conditions['limit'] = $limit;
        return $this->db->fetchAll($query, $conditions);
    }

    /**
     * Finds a single record based on conditions.
     * 
     * @param array $conditions Associative array of column-value pairs.
     * @return array|null Returns the first matching record or null if none found.
     */
    public function first(array $conditions): ?array
    {
        $result = $this->find($conditions, "DESC", 1);
        return $result[0] ?? null;
    }
}
