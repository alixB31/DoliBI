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

  console.log("dates :", dates);
  console.log("Quantités :", quantites);
  console.log("Montants :", montants);

new Chart(ctx, {
  type: 'line',
  data: {
    labels: [dates],
    datasets: [{
      label: 'Quantité',
      data: [quantites],
      yAxisID: 'quantites-y',
      borderColor: 'blue',
      borderWidth: 1
    }, {
      label: 'Montant',
      data: [montants],
      yAxisID: 'montant-y',
      borderColor: 'red',
      borderWidth: 1
    }],
  },
  options: {
    scales: {
      yAxes: [{
        id: 'quantites-y',
        type: 'linear',
        position: 'left',
        scaleLabel: {
          display: true,
          labelString: 'Quantités'
        }
      }, {
        id: 'montant-y',
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

