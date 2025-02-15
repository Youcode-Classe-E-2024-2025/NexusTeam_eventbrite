<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use Google\Client;
use Google\Service\Oauth2;
use App\Models\User;
use App\Core\Database;
use Exception;
use Dotenv\Dotenv;

class GoogleSignInController {
    public function __construct() {
        // Charger les variables d'environnement à partir du fichier .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Chemin absolu pour charger le .env
        $dotenv->load();
    }

    public function handlegoogle() {
        session_start();

        // Configuration du client Google avec des variables d'environnement
        $client = new Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']); // Utilisation de GOOGLE_CLIENT_ID depuis .env
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']); // Utilisation de GOOGLE_CLIENT_SECRET depuis .env
        $client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']); // Utilisation de GOOGLE_REDIRECT_URI depuis .env
        $client->addScope("email");
        $client->addScope("profile");
        $client->setPrompt('select_account'); // 🔹 Assure que l'utilisateur voit la fenêtre de sélection de compte

        // Vérifier si le code est présent dans l'URL après l'authentification
        if (!isset($_GET['code'])) {
            $authUrl = $client->createAuthUrl();
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit;
        } else {
            // Récupérer le token d'accès via le code d'authentification
            $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $oauth2 = new Oauth2($client);
            $userInfo = $oauth2->userinfo->get();

            // Initialisation du modèle User pour interagir avec la base de données
            $userModel = new User(Database::getInstance()->getConnection());

            // Vérifier si l'utilisateur existe déjà
            $existingUser = $userModel->getUserByEmail($userInfo->email);

            if (!$existingUser) {
                // Si l'utilisateur n'existe pas, on le crée
                $userModel->setFullName($userInfo->name);
                $userModel->setEmail($userInfo->email);
                $userModel->setPassword(""); // Pas de mot de passe pour un utilisateur Google
                $userModel->setRole("participant"); // Par défaut, rôle "participant"
                $userModel->setAvatar($userInfo->picture);
                $userModel->createUser(); // Créer l'utilisateur dans la base de données
                $existingUser = $userModel->getUserByEmail($userInfo->email); // Récupérer l'utilisateur nouvellement créé
            }

            // Enregistrer les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $existingUser['id_user'];
            $_SESSION['user_email'] = $existingUser['email'];
            $_SESSION['user_role'] = $existingUser['role'];

            // Rediriger l'utilisateur selon son rôle
            switch ($_SESSION['user_role']) {
                case 'admin':
                    header("Location: /admin-dashboard");
                    break;
                case 'organisateur':
                    header("Location: /organisateur-dashboard");
                    break;
                case 'participant':
                default:
                    header("Location: /participant-dashboard");
                    break;
            }
            exit;
        }
    }
}
