<?php

namespace App\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        session_start();
    
        if (!isset($_SESSION["price"])) {
            die("Erreur : informations de réservation manquantes.");
        }
    
        $price = $_SESSION["price"];
    
        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
    
        // Contenu du PDF
        $html = "
            <h1>Confirmation de Réservation</h1>
            <p><strong>Montant Payé:</strong> $price USD</p>
            <p>Merci pour votre réservation !</p>
        ";
    
        // Générer le PDF
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

