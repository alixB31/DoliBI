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
        quantites: {
          id: 'quantites',
          type: 'linear',
          position: 'left',
          scaleLabel: {
            display: true,
            labelString: 'Quantités'
          },
          grid: {
            tickColor: 'blue' //couleur de l'axe Y
          },
          ticks: {
            color: 'blue', //couleur de l'axe Y
          }
        }, 
        montant: {
          id: 'montant',
          type: 'linear',
          position: 'right',
          scaleLabel: {
            display: true,
            labelString: 'Montants'
          },
          grid: {
            tickColor: 'red' //couleur de l'axe Y
          },
          ticks: {
            color: 'red', //couleur de l'axe Y
          }
        }
      }
    }
});
