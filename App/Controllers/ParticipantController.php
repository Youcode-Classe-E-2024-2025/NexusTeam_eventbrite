<?php

namespace App\Controllers;
use App\Core\Views;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


use App\Models\User;
use App\Core\Database;
use Exception;



class ParticipantController {
    public function index(){
          // Vérifie si l'utilisateur est connecté
    session_start();
    if (isset($_SESSION['user'])) {
        // Passer les données utilisateur à la vue
        $user = $_SESSION['user'];
        Views::render('participant', ['user' => $user]);
    } else {
        // Rediriger l'utilisateur vers la page de connexion si non connecté
        header("Location: /login");
        exit;
    }
    }
}