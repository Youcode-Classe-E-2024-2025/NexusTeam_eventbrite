<?php
namespace App\Models;
use App\Core\Database;

class Ticket {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createTicket($eventId, $participantId, $ticketType, $price, $qrCodePath) {
        $query = "INSERT INTO tickets (event_id, participant_id, ticket_type, price, qr_code) 
                  VALUES (:event_id, :participant_id, :ticket_type, :price, :qr_code) RETURNING id";
        
        $stmt = $this->db->prepareExecute($query, [
            ':event_id' => $eventId,
            ':participant_id' => $participantId,
            ':ticket_type' => $ticketType,
            ':price' => $price,
            ':qr_code' => $qrCodePath
        ]);
    
        if ($stmt->rowCount() > 0) {
            // Récupérer l'ID du ticket nouvellement créé
            $ticketId = $stmt->fetchColumn();
            // Sauvegarder l'ID du ticket dans la session
            $_SESSION["ticket_id"] = $ticketId;
            return $ticketId;
        }
    
        return false;
    }
    
    
    public function ticketExists($ticketId) {
        $query = "SELECT COUNT(*) FROM tickets WHERE id = :ticket_id";
        $stmt = $this->db->prepareExecute($query, [':ticket_id' => $ticketId]);
        return $stmt->fetchColumn() > 0;
    }
}


// $ticketModel = new Ticket();

// $eventId = 1;          
// $participantId = 123;  
// $ticketType = 'VIP'; 
// $price = 50.00;       
// $qrCodePath = 'path/to/qrcode.png'; 

// $ticketModel->createTicket($eventId, $participantId, $ticketType, $price, $qrCodePath);
// ?>
