<?php
namespace App\Controllers;

use App\Models\User;
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

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $user = new User(Database::getInstance()->getConnection());
        $userData = $user->getUserById($userId);

        echo $this->twig->render('avatar.twig', ['user' => $userData]);
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

                header("Location: /participant");
                exit;

            } catch (Exception $e) {
                echo "Erreur: " . $e->getMessage();
            }
        }
    }
}
