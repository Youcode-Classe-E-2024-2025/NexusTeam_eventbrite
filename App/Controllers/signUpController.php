<?php
namespace App\Controllers;

class signUpController{
    
    // // Affiche la page d'inscription
    public function signUp()
    {
        require_once __DIR__ . "/../Views/signUp.twig";
    }

}