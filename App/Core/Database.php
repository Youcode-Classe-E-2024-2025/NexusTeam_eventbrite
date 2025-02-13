<?php
namespace App\Core;
use PDO;
use PDOException;
use PDOStatement;
use Exception;

/**
 * @Database
 */

class Database
{
    private static ?Database $instance = null;
    private string $host;
    private string $port;
    private string $dbname;
    private string $user;
    private string $password;
    private ?PDO $pdo = null;

    /**
     * Constructs the database with the defined global constants
     * found in the config file
     *
     * and initializes the database
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->host = DB_HOST;
        $this->port = DB_PORT;
        $this->dbname = DB_NAME;
        $this->user = DB_USER;
        $this->password = DB_PASS;
        $this->initDatabase();
    }

    /**
     * returns an instance of the database class
     * if no instance exists it creates a new one
     *
     * @return Database
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Initializes the database by checking if a database with the defined name exists
     * If not, it creates a new one and connects to it
     *
     * @throws Exception
     */
    private function initDatabase(): void
    {
        $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port;

        try {
            $tempPDO = new PDO($dsn, $this->user, $this->password);
            $tempPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $tempPDO->prepare("SELECT 1 FROM pg_database WHERE datname = :dbname");
            $stmt->execute(['dbname' => $this->dbname]);
            $exists = $stmt->fetchColumn();

            if (!$exists) {
                $tempPDO->exec("CREATE DATABASE " . $this->dbname);
            }

            $tempPDO = null;

            $this->connect();
        } catch (PDOException|Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * The actual connection to the database
     *
     * @throws Exception
     */
    private function connect(): void
    {
        $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname;
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * Prepares a sql statement and executes it
     * Supply it with an array of bind parameters
     *
     * @param string $sql
     * @param array $params
     * @return PDOStatement|null
     * @throws Exception
     */
    public function prepareExecute(string $sql, array $params = []): ?PDOStatement
    {
        if ($this->pdo === null) {
            $this->connect();
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Uses the prepareExecute method and fetches an assoc array
     *
     * @param string $sql
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->prepareExecute($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches a single element
     *
     * @param string $sql
     * @param array $params
     * @return array|null
     * @throws Exception
     */
    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->prepareExecute($sql, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Fetches a column
     *
     * @param string $sql
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function fetchCol(string $sql, array $params = []): array
    {
        $stmt = $this->prepareExecute($sql, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Executes a statement and returns the number of affected rows
     *
     * @param string $sql
     * @param array $params
     * @return int
     * @throws Exception
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->prepareExecute($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Returns the last inserted id into the database
     *
     * @return int
     * @throws Exception
     */
    public function lastInsertId(): int
    {
        if ($this->pdo === null) {
            $this->connect();
        }
        return intval($this->pdo->lastInsertId());
    }

    // Nouvelle méthode publique pour accéder à la connexion PDO
    public function getConnection(): PDO {
        if ($this->pdo === null) {
            $this->connect();
        }
        return $this->pdo;
    }
}