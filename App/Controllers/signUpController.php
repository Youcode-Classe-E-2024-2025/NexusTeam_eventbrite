<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


use App\Models\User;
use App\Core\Database;
use Exception;

class SignUpController {
        private $twig;
    
        public function __construct() {
            $loader = new FilesystemLoader(__DIR__ . '/../Views');
            $this->twig = new Environment($loader);
        }
    
        public function signUp() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
    
            echo $this->twig->render('signUp.twig', ['csrf_token' => $_SESSION['csrf_token']]);
        }
    
    
    

    public function register() {
        try {
            // Démarrer la session si ce n'est pas déjà fait
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Vérifier le token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception("Requête invalide (CSRF détecté).");
            }

            // Récupérer les données du formulaire
            $fullName = trim($_POST['full_name'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? 'participant';

            // Validation des champs
            if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
                throw new Exception("Tous les champs sont requis.");
            }

            if ($password !== $confirmPassword) {
                throw new Exception("Les mots de passe ne correspondent pas.");
            }

            // Sécuriser le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Instancier le modèle User
            $user = new User(Database::getInstance()->getConnection());

            // Vérifier si l'email existe déjà
            if ($user->getUserByEmail($email)) {
                throw new Exception("Cet email est déjà utilisé.");
            }

            // Définir les propriétés de l'utilisateur
            $user->setFullName($fullName);
            $user->setEmail($email);
            $user->setPassword($hashedPassword);
            $user->setRole($role);

            // Créer l'utilisateur dans la base de données
            if ($user->createUser()) {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = $role;

                echo "Inscription réussie. Vous êtes connecté.";
                // Rediriger selon le rôle
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

            } else {
                throw new Exception("Une erreur est survenue lors de l'inscription.");
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }
}
