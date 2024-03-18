document.addEventListener('DOMContentLoaded', function() {
    var btnAjouterURL = document.getElementById('btnAjouterURL');
    var formulaireAjoutURL = document.getElementById('formulaireAjoutURL');

    // Masquer le formulaire au chargement de la page
    formulaireAjoutURL.style.display = 'none';

    // Ajouter un écouteur d'événements au clic sur le bouton
    btnAjouterURL.addEventListener('click', function(event) {
        event.preventDefault(); // Empêcher le comportement par défaut du bouton

        // Afficher ou masquer le formulaire en fonction de son état actuel
        if (formulaireAjoutURL.style.display === 'none') {
            formulaireAjoutURL.style.display = 'block';
        } else {
            formulaireAjoutURL.style.display = 'none';
        }
    });
});

