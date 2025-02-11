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
    private $avatar;
    private $registrationDate;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getId() {
        return $this->id;
    }

    public function setFullName($fullName) {
        $this->fullName = htmlspecialchars(trim($fullName));
    }
    public function getFullName() {
        return $this->fullName;
    }

    public function setEmail($email) {
        $this->email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password) {
        $this->password = $password; // Ne pas re-hasher ici
    }
    public function getPassword() {
        return $this->password;
    }

    public function setRole($role) {
        $this->role = in_array($role, ['participant', 'organizer', 'admin']) ? $role : 'participant';
    }
    public function getRole() {
        return $this->role;
    }

    public function setAvatar($avatar) {
        $this->avatar = filter_var(trim($avatar), FILTER_SANITIZE_URL); // URL safe avatar path
    }
    public function getAvatar() {
        return $this->avatar;
    }

    public function getRegistrationDate() {
        return $this->registrationDate;
    }

    // Récupérer un utilisateur par email
    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer un utilisateur par ID
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id_user = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer un nouvel utilisateur dans la base de données
    public function createUser(): bool {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role, avatar) VALUES (:full_name, :email, :password, :role, :avatar)");
        return (bool) $stmt->execute([
            'full_name' => $this->fullName,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
            'avatar' => $this->avatar
        ]);
    }



    public function displayUser(){
        $stmt = $this->pdo->prepare("SELECT * from users");
        $stmt->execute(); 
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users ;
    }

    // public function approveUser(){
    //     $stmt = $this->pdo
    // }
}
