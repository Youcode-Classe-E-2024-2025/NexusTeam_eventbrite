    document.getElementById("login-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Empêche l'envoi du formulaire si validation échoue

        const email = document.querySelector('input[placeholder="Email"]');
        const password = document.querySelector('input[placeholder="Password"]');

        let errorMessage = ""; // Stocke les erreurs pour une alerte unique

        // Regex pour valider l'email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            errorMessage += "Veuillez entrer une adresse e-mail valide.\n";
        }

        // Vérifier si le mot de passe est vide
        if (password.value.trim() === "") {
            errorMessage += "Le mot de passe ne peut pas être vide.\n";
        }

        // Afficher les erreurs si présentes
        if (errorMessage !== "") {
            alert(errorMessage);
            return;
        }

        // Si toutes les validations passent, soumettre le formulaire
        alert("Connexion réussie !");
        this.submit();
    });
