<?php
namespace App\Controllers;

use App\Models\Ticket;
use App\Core\Views;
use Exception;

class TicketController {

    private $ticketModel;

    public function __construct() {
        // Initialisation du modèle Ticket
        $this->ticketModel = new Ticket();
    }

    /**
     * Méthode pour créer un ticket
     * Reçoit les données depuis la requête et les passe au modèle
     */

     public function index() {
        Views::render('reservation_form'); 
    }
    public function store() {
        session_start();
        // Vérifier si les données sont présentes dans la requête (par exemple, via POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données envoyées dans la requête POST
            $eventId = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
            $participantId = isset($_POST['participant_id']) ? (int)$_POST['participant_id'] : 0;
            $ticketType = isset($_POST['ticket_type']) ? $_POST['ticket_type'] : '';
            $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
            $qrCodePath = isset($_POST['qr_code']) ? $_POST['qr_code'] : 'code';
            

            // Valider les données
            if ($eventId && $participantId && $ticketType && $price && $qrCodePath) {
                try {
                    // Appeler la méthode createTicket du modèle pour insérer un ticket
                    $result = $this->ticketModel->createTicket($eventId, $participantId, $ticketType, $price, $qrCodePath);
                    $_SESSION["reservation_id"] = $result;
                    $_SESSION["price"] = $price;
                    // Vérifier si l'insertion a réussi
                    if ($result) {
                        // echo "Ticket créé avec succès !";
                        header("Location: /payment");


                    } else {
                        echo "L'insertion du ticket a échoué.";
                    }
                } catch (Exception $e) {
                    // Gérer les exceptions en cas d'erreur
                    echo "Erreur lors de l'insertion : " . $e->getMessage();
                }
            } else {
                echo "Données invalides ou manquantes.";
            }
        } else {
            echo "Méthode non autorisée.";
        }
    }
}
