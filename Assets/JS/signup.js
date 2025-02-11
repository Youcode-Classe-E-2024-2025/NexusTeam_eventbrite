
    document.getElementById("signup-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Empêche l'envoi du formulaire si validation échoue

        const name = document.querySelector('input[placeholder="Full Name"]');
        const email = document.querySelector('input[placeholder="Email"]');
        const password = document.querySelector('input[placeholder="Password"]');
        const confirmPassword = document.querySelector('input[placeholder="Confirm Password"]');

        let errorMessage = ""; // Stocke les erreurs pour une alerte unique

        // Regex pour valider le nom (lettres, espaces, accents et tirets)
        const nameRegex = /^[a-zA-ZÀ-ÖØ-öø-ÿ\s-]+$/;
        if (!nameRegex.test(name.value.trim())) {
            errorMessage += "Le nom doit contenir uniquement des lettres, espaces ou tirets.\n";
        }

        // Regex pour valider l'email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            errorMessage += "Veuillez entrer une adresse e-mail valide.\n";
        }

        // Regex pour le mot de passe (au moins une majuscule, une minuscule, un chiffre et un caractère spécial)
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        if (!passwordRegex.test(password.value)) {
            errorMessage += "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.\n";
        }

        // Vérifier si les mots de passe correspondent
        if (password.value !== confirmPassword.value) {
            errorMessage += "Les mots de passe ne correspondent pas.\n";
        }

        // Afficher les erreurs si présentes
        if (errorMessage !== "") {
            alert(errorMessage);
            return;
        }

        // Si toutes les validations passent, soumettre le formulaire
        alert("Inscription réussie !");
        this.submit();
    });

