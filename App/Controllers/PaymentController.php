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
                "cancel_url" => "http://localhost/event",
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
        $options->set('isRemoteEnabled', true); // Activer le support des images distantes
        $dompdf = new Dompdf($options);

        $imagePath = __DIR__ . "/../../Assets/image/image.png"; // Chemin absolu
        $randomName = "Client_" . uniqid();
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageSrc = 'data:image/png;base64,' . $imageData; // Encodage Base64
        } else {
            die("Erreur : L'image n'existe pas.");
        }


        $html = "
        <style>
            h1 { color: #2c3e50; text-align: center; }
            p { font-size: 14px; }
            .box {
                border: 2px solid #2c3e50;
                padding: 15px;
                border-radius: 8px;
                background-color: #f8f9fa;
                text-align: center;
            }
            .highlight {
                color: #e74c3c;
                font-weight: bold;
            }
            img {
                width: 150px;
                height: auto;
                margin-top: 10px;
            }
        </style>

        <h1>Confirmation de Réservation</h1>
        <div class='box'>
            <p><strong>Réservation ID :</strong> <span class='highlight'>$reservationId</span></p>
            <p><strong>Nom du Client :</strong> $randomName</p> <!-- Nom généré automatiquement -->            <p><strong>Montant Payé :</strong> <span class='highlight'>" . number_format($price, 2) . " USD</span></p>
            <img src='$imageSrc' alt='QR Code'>
            <p>Merci pour votre réservation !</p>
        </div>
        ";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Confirmation_Reservation.pdf"');
        echo $dompdf->output();
        exit();

    }
}

