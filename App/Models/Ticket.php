<?php
namespace App\Models;
use App\Core\Database;

class Ticket {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createTicket($eventId, $participantId, $ticketType, $price, $qrCodePath) {
        // Requête SQL pour insérer un ticket
        $query = "INSERT INTO tickets (event_id, participant_id, ticket_type, price, qr_code) 
                  VALUES (:event_id, :participant_id, :ticket_type, :price, :qr_code)";
        
        // Préparation et exécution de la requête
        $stmt = $this->db->prepareExecute($query, [
            ':event_id' => $eventId,
            ':participant_id' => $participantId,
            ':ticket_type' => $ticketType,
            ':price' => $price,
            ':qr_code' => $qrCodePath
        ]);

        // Message de succès
        if ($stmt->rowCount() > 0) {
            echo "Ticket créé avec succès !\n";
        } else {
            echo "L'insertion du ticket a échoué.\n";
        }

        return $stmt->rowCount() > 0;
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
