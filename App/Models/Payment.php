<?php
namespace App\Models;

use App\Core\Database;

class Payment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function savePayment($ticketId, $amount, $method, $status) {
        $query = "INSERT INTO payments (ticket_id, amount, payment_method, status) 
                  VALUES (:ticket_id, :amount, :payment_method, :status)";
        
        $stmt = $this->db->prepareExecute($query, [
            ':ticket_id' => $ticketId,
            ':amount' => $amount,
            ':payment_method' => $method,
            ':status' => $status
        ]);

        return $stmt->rowCount() > 0;
    }
}
