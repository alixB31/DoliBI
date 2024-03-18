document.addEventListener('DOMContentLoaded', function() {
  var firstForm = document.getElementById('first-form');
  var secondForm = document.getElementById('second-form');

  // Masquer le deuxième formulaire au chargement de la page
  secondForm.style.display = 'none';

  // Écouter la soumission du premier formulaire
  firstForm.addEventListener('submit', function(event) {
      event.preventDefault(); // Empêcher l'envoi du formulaire

      // Vérifier si le champ de saisie du nom du fournisseur est rempli
      var nomFournisseurInput = document.getElementById('test');
      if (nomFournisseurInput.value.trim() !== '') {
          // Si c'est le cas, afficher le deuxième formulaire
          secondForm.style.display = 'block';
      } else {
          // Sinon, afficher un message d'erreur ou effectuer une autre action
          alert('Veuillez saisir un nom de fournisseur.');
      }
  });

  // Récupérer le canvas pour le graphique
  const ctx = document.getElementById('myChart');

  // Extraire les données JSON et les stocker dans une variable JavaScript
  var donnees = JSON.parse(document.getElementById('donnees').textContent);

  // Extraire les données
  var dates = [];
  var quantites = [];
  var montants = [];

  for (var i = 0; i < donnees.length; i++) {
    dates.push(donnees[i].date);
    quantites.push(donnees[i].quantite);
    montants.push(donnees[i].montant);
  }

  // Créer le graphique une fois que toutes les données sont prêtes
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: dates,
      datasets: [{
        label: 'Quantité',
        data: quantites,
        yAxisID: 'quantites',
        borderColor: 'blue',
        borderWidth: 1
      }, {
        label: 'Montant',
        data: montants,
        yAxisID: 'montant',
        borderColor: 'red',
        borderWidth: 1
      }],
    },
    options: {
      scales: {
        yAxes: [{
          id: 'quantites',
          type: 'linear',
          position: 'left',
          scaleLabel: {
            display: true,
            labelString: 'Quantités'
          }
        }, {
          id: 'montant',
          type: 'linear',
          position: 'right',
          scaleLabel: {
            display: true,
            labelString: 'Montants'
          }
        }]
      }
    }
  });
});
