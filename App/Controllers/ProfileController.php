<?php
namespace App\Controllers;
use App\Models\User;
use App\Models\Profile;
use App\Core\Database;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Exception;

class ProfileController {
    private $twig;

    public function __construct() {
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $this->twig = new Environment($loader);
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Vérifier si l'utilisateur est bien connecté
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            header("Location: /login");
            exit;
        }
    
        $userId = $_SESSION['user']['id'];
    
        if ($userId === null) {
            throw new Exception("Utilisateur non connecté ou session invalide.");
        }
    
        $user = new User(Database::getInstance()->getConnection());
        $userData = $user->getUserById($userId);
    
        // Vérifier que l'utilisateur existe bien en base de données
        if (!$userData) {
            throw new Exception("Utilisateur introuvable.");
        }
    
        // Récupérer les événements réservés par l'utilisateur
        $profile = new Profile();
        $events = $profile->getUserEvents($userId);
    
        // Passer les données à Twig
        echo $this->twig->render('profile.twig', [
            'user' => $userData,
            'events' => $events
        ]);
    }
    

    public function updateProfileImage() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile-image'])) {
            try {
                $userId = $_SESSION['user']['id'];
                $user = new User(Database::getInstance()->getConnection());

                $file = $_FILES['profile-image'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                $fileError = $file['error'];
                $fileSize = $file['size'];

                if ($fileError !== 0) {
                    throw new Exception("Erreur lors du téléchargement de l'image.");
                }

                if ($fileSize > 5000000) {
                    throw new Exception("L'image est trop grande. Max: 5 Mo.");
                }

                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($fileExt, $allowedExtensions)) {
                    throw new Exception("Format non autorisé.");
                }

                $newFileName = uniqid('avatar_', true) . '.' . $fileExt;
                $uploadDirectory = __DIR__ . '/../../app/uploads/';
                $filePath = $uploadDirectory . $newFileName;

                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0777, true);
                }

                if (!move_uploaded_file($fileTmpName, $filePath)) {
                    throw new Exception("Erreur lors du déplacement du fichier.");
                }

                $user->updateProfileImage($userId, $newFileName);
                $_SESSION['user']['avatar'] = $newFileName;

                header("Location: /profile");
                exit;

            } catch (Exception $e) {
                echo "Erreur: " . $e->getMessage();
            }
        }
    }
}
