function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

// Récupérer le canvas pour le graphique
const ctx = document.getElementById('myChart');

// Extraire les données JSON et les stocker dans une variable JavaScript
var donnees = JSON.parse(document.getElementById('donnees').textContent);

// Extraire les données
var banque = [];
var solde = [];
var backgroundColors = [];
var borderColors = [];

for (var i = 0; i < donnees.length; i++) {
    banque.push(donnees[i].banque);
    solde.push(donnees[i].solde);
    borderColors.push(getRandomColor());
    backgroundColors.push(getRandomColor());
}


// Créer le graphique une fois que toutes les données sont prêtes
new Chart(ctx, {
    type: 'pie',
    data: {
      labels: banque, // Libellés des étapes séquentielles
      datasets: [{
        label: 'Solde',
        data: solde, // Tableau des données de solde
        backgroundColor: backgroundColors,
        borderColor: borderColors,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
            display: true,
            text: 'Diagramme de répartition des soldes dans chaque banque'
          }
        }
      }
    });
  