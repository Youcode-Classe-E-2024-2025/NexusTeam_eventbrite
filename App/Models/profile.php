<?php

namespace App\Models;

use App\Core\Database;
use Exception;

class Profile {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère les événements réservés par un utilisateur
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getUserEvents(int $userId): array {
        try {
            $query = "
                SELECT e.id, e.title, e.start_date, e.location, e.description, e.promotional_image, t.ticket_type, t.price, t.status
                FROM tickets t
                JOIN events e ON t.event_id = e.id
                WHERE t.participant_id = :userId
                ORDER BY e.start_date DESC
            ";
    
            return $this->db->fetchAll($query, [':userId' => $userId]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des événements : " . $e->getMessage());
        }
    }
    
    
}
