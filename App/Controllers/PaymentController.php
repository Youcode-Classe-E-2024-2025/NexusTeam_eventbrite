<?php

namespace App\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController {
    public function pay() {
        session_start();
        
        if (!isset($_SESSION["reservation_id"]) || !isset($_SESSION["price"])) {
            die("Réservation introuvable.");
        }

        $price = $_SESSION["price"];
        $reservation_id = $_SESSION["reservation_id"];

        // Charger la configuration
        $config = require __DIR__ . "/../Config/config.php";
        Stripe::setApiKey($config["STRIPE_SECRET_KEY"]);

        try {
            $session = Session::create([
                "mode" => "payment",
                "success_url" => "http://localhost/success?reservation_id=" . $reservation_id,
                "cancel_url" => "http://localhost/cancel",
                "line_items" => [[
                    "quantity" => 1,
                    "price_data" => [
                        "currency" => "usd",
                        "unit_amount" => $price * 100, // Correction : conversion en cents
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
}
