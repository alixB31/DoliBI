document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'ajout d'URL
    function initAjoutURL() {
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
    }

    // Gestion de la conservation de la sélection précédente dans la liste déroulante
    function initConservationSelection() {
        var selectElement = document.getElementById("urlExistant");
        var lastSelected = localStorage.getItem("lastSelectedOption");

        if (lastSelected) {
            selectElement.value = lastSelected;
        }

        selectElement.addEventListener("change", function(event) {
            localStorage.setItem("lastSelectedOption", event.target.value);
        });
    }

    // Gestion de la suppression d'URL
    function initSuppressionURL() {
        var btnSupprimerURL = document.getElementById('btnSupprimerURL');

        btnSupprimerURL.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du lien

            var selectedUrl = document.getElementById('urlExistant').value;

            var form = document.createElement('form');
            form.method = 'post';
            form.action = '?controller=UtilisateurCompte&action=supprimerUrl';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'urlExistant';
            input.value = selectedUrl;

            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });
    }

    // Initialisation des fonctionnalités
    initAjoutURL();
    initConservationSelection();
    initSuppressionURL();
});
