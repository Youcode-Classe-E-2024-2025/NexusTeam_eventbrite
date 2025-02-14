<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';
use Facebook\Facebook;
use App\Models\User;
use App\Core\Database;
use Exception;

class FacebookSignInController {
    public function handle() {
        $fb = new Facebook([
            'app_id' => 'VOTRE_APP_ID',
            'app_secret' => 'VOTRE_APP_SECRET',
            'default_graph_version' => 'v12.0',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; 
        $loginUrl = $helper->getLoginUrl('http://localhost:8000/facebook-callback', $permissions);

        header('Location: ' . $loginUrl);
        exit;
    }

    public function callback() {
        session_start();
        $fb = new Facebook([
            'app_id' => 'VOTRE_APP_ID',
            'app_secret' => 'VOTRE_APP_SECRET',
            'default_graph_version' => 'v12.0',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
            $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
            $user = $response->getGraphUser();

            // Initialisation du modèle User
            $userModel = new User(Database::getInstance()->getConnection());

            // Vérifier si l'utilisateur existe déjà
            $existingUser = $userModel->getUserByEmail($user['email']);

            if (!$existingUser) {
                // Si l'utilisateur n'existe pas, on le crée
                $userModel->setFullName($user['name']);
                $userModel->setEmail($user['email']);
                $userModel->setPassword(""); // Pas de mot de passe pour un utilisateur Facebook
                $userModel->setRole("participant"); // Par défaut, rôle "participant"
                $userModel->setAvatar($user['picture']['url']);
                $userModel->createUser(); // Créer l'utilisateur dans la base de données
                $existingUser = $userModel->getUserByEmail($user['email']); // Récupérer l'utilisateur nouvellement créé
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

        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            exit;
        }
    }
}
