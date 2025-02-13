<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Database;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class LoginController {
    private $twig;

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $this->twig = new Environment($loader);
    }

    // Afficher le formulaire de connexion
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Générer un token CSRF s'il n'existe pas
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Afficher le formulaire de login avec le token CSRF
        echo $this->twig->render('login.twig', ['csrf_token' => $_SESSION['csrf_token']]);
    }

    // Authentifier l'utilisateur
    public function authenticate() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Vérification du token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception("Requête invalide (CSRF détecté).");
            }

            // Récupérer les données du formulaire
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            // Vérifier si les champs sont vides
            if (empty($email) || empty($password)) {
                throw new Exception("Veuillez remplir tous les champs.");
            }

            // Récupérer l'utilisateur
            $user = new User(Database::getInstance()->getConnection());
            $userData = $user->getUserByEmail($email);

            if (!$userData) {
                throw new Exception("Identifiants incorrects.");
            }

            // Vérifier le mot de passe hashé
            if (!password_verify($password, $userData['password'])) {
                throw new Exception("Identifiants incorrects.");
            }

            // Stocker les infos de l'utilisateur en session
            $_SESSION['user_id'] = $userData['id_user'];
            $_SESSION['user_email'] = $userData['email'];
            $_SESSION['user_role'] = $userData['role'];

            // Rediriger selon le rôle
            switch ($_SESSION['user_role']) {
                case 'admin':
                    header("Location: /admin-dashboard");
                    break;
                case 'organizer':
                    header("Location: /organisateur-dashboard");
                    break;
                case 'participant':
                default:
                    header("Location: /participant-dashboard");
                    break;
            }
            exit;
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    // Déconnecter l'utilisateur
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header("Location: /login");
        exit;
    }
}
