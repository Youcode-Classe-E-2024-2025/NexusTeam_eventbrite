<?php

namespace App\Model;

use App\Core\Database;

class Ticket {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllTickets(): array {
        return $this->db->fetchAll("SELECT * FROM tickets");
    }

    public function buyTicket(int $userId, string $ticketType, float $price): bool {
        $sql = "INSERT INTO tickets (user_id, type, price) VALUES (:user_id, :type, :price)";
        $params = [
            'user_id' => $userId,
            'type' => $ticketType,
            'price' => $price
        ];
        return $this->db->execute($sql, $params) > 0;
    }
}
