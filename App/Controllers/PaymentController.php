<?php

namespace App\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Payment;
use App\Models\Ticket;
use App\Core\Views;


class PaymentController {
    public function pay() {
        session_start();
        
        if (!isset($_SESSION["reservation_id"]) || !isset($_SESSION["price"])) {
            die("Réservation introuvable.");
        }

        $price = $_SESSION["price"];

        // Charger la configuration
        $config = require __DIR__ . "/../Config/config.php";
        Stripe::setApiKey($config["STRIPE_SECRET_KEY"]);

        try {
            $session = Session::create([
                "mode" => "payment",
                "success_url" => "http://localhost/payment/success",
                "cancel_url" => "http://localhost/cancel",
                "line_items" => [[
                    "quantity" => 1,
                    "price_data" => [
                        "currency" => "usd",
                        "unit_amount" => $price * 100, 
                        "product_data" => [
                            "name" => "Réservation Événement",
                        ],
                    ],
                ]],
            ]);

            header("Location: " . $session->url);
            exit();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            echo "Erreur de paiement : " . $e->getMessage();
        }
    }

    public function success() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if (!isset($_SESSION["reservation_id"]) || !isset($_SESSION["price"])) {
            die("Erreur : informations de réservation manquantes.");
        }
    
        $reservationId = $_SESSION["reservation_id"];
        $price = $_SESSION["price"];
        var_dump($_SESSION);;
        // Vérifier si le ticket existe
        if (!isset($_SESSION["ticket_id"])) {
            die("Erreur : ID de ticket manquant dans la session.");
        }
    
        $ticketId = $_SESSION["ticket_id"];
    
        // Enregistrer le paiement dans la base de données
        $paymentModel = new Payment();
        $paymentSaved = $paymentModel->savePayment($ticketId, $price, "Stripe", "successful");
    
        if (!$paymentSaved) {
            error_log("Échec de l'enregistrement du paiement pour la réservation ID: " . $reservationId);
        }


        // Génération du PDF de confirmation
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $html = "
            <h1>Confirmation de Réservation</h1>
            <p><strong>Réservation ID:</strong> $reservationId</p>
            <p><strong>Montant Payé:</strong> " . number_format($price, 2) . " USD</p>
            <p>Merci pour votre réservation !</p>
        ";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Envoie le PDF au navigateur
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Confirmation_Reservation.pdf"');
        echo $dompdf->output();
        exit();
    }
}

