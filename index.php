<?php

require_once "vendor/autoload.php";
require_once "Routes/web.php";

// \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

// try {
//     $session = \Stripe\Checkout\Session::create([
//         "mode" => "payment",
//         "success_url" => "http://localhost/success.php",
//         "cancel_url" => "http://localhost/cancel.php", // Optionnel mais recommandé
//         "line_items"=> [
//             [
//                 "quantity" => 1,
//                 "price_data" => [
//                     "currency" => "usd",
//                     "unit_amount" => 2000, // 20.00 USD
//                     "product_data" => [
//                         "name" => "Nom du produit",
//                     ],
//                 ],
//             ]
//         ],
//     ]);

//     http_response_code(303);
//     header("Location: " . $session->url);
// } catch (\Stripe\Exception\ApiErrorException $e) {
//     echo 'Erreur lors de la création de la session : ' . $e->getMessage();
// }
// exit();
