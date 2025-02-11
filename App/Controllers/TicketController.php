<?php

namespace App\Controllers;

use App\Model\Ticket;

class TicketController {
    public function index() {
        $ticketModel = new Ticket();
        $tickets = $ticketModel->getAllTickets();
        require 'app/views/tickets/index.php';
    }

    public function buy() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
            $ticketType = filter_input(INPUT_POST, 'ticket_type', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

            if ($userId && $ticketType && $price) {
                $ticketModel = new Ticket();
                $success = $ticketModel->buyTicket($userId, $ticketType, $price);

                if ($success) {
                    header('Location: /payment/checkout');
                    exit;
                } else {
                    echo "Erreur lors de l'achat du ticket.";
                }
            } else {
                echo "Données invalides.";
            }
        }
    }
}
