<?php
namespace App\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Payment;

class PaymentController{
    public function createSession() {
        require_once __DIR__ . '/../../vendor/autoload.php';

        Stripe::setApiKey('sk_test_ta_cle_secrete');

        $ticketId = $_POST['ticket_id']; 
        $amount = $_POST['amount']; 

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Ticket Event'],
                        'unit_amount' => $amount * 100, 
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://localhost/payment/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => 'http://localhost/payment/cancel',
            ]);

            echo json_encode(['id' => $session->id]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function success() {
        echo "Paiement réussi !";
    }

    public function cancel() {
        echo "Paiement annulé.";
    }
}
