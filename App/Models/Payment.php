<?php
namespace App\Models;

use App\Core\Database;

class Payment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function savePayment($ticketId, $amount, $method, $status) {
        // Vérifier que $status est valide
        $validStatuses = ['successful', 'failed', 'refunded'];
        if (!in_array($status, $validStatuses)) {
            error_log("Statut de paiement invalide: " . $status);
            return false;
        }
    
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
