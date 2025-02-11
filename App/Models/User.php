<?php
namespace App\Models;

use PDO;
use Exception;

class User {
    private $pdo;
    private $id;
    private $fullName;
    private $email;
    private $password;
    private $role;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getId() {
        return $this->id;
    }
    

    public function setFullName($fullName) { $this->fullName = htmlspecialchars(trim($fullName)); }
    public function getFullName() { return $this->fullName; }

    public function setEmail($email) { $this->email = filter_var(trim($email), FILTER_SANITIZE_EMAIL); }
    public function getEmail() { return $this->email; }

    public function setPassword($password) { 
        $this->password = $password; // Ne pas re-hasher ici
    }
        public function getPassword() { return $this->password; }

    public function setRole($role) { $this->role = in_array($role, ['participant', 'organisateur', 'admin']) ? $role : 'participant'; }
    public function getRole() { return $this->role; }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id_user = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser() {
        $stmt = $this->pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (:full_name, :email, :password, :role)");
        return $stmt->execute([
            'full_name' => $this->fullName,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role
        ]);
    }
}
