const ctx = document.getElementById('myChart');
// Extrais les données JSON et les stocker dans une variable JavaScript
var donneesAchetes = JSON.parse(document.getElementById('achetes').textContent);
var donneesVendues = JSON.parse(document.getElementById('vendues').textContent);


// Extraire les données
var dates = [];
var achetes = [];
var vendues = [];

for (var i = 0; i < donneesAchetes.length; i++) {
  dates.push(donneesAchetes[i].date);
  achetes.push(donneesAchetes[i].quantite);
}
for (var i = 0; i < donneesVendues.length; i++) {
    dates.push(donneesVendues[i].date);
    vendues.push(donneesVendues[i].quantite);
}
// Trouver la première et la dernière date dans le tableau
var premiereDate = new Date(Math.min.apply(null, dates.map(function(date) {
    return new Date(date);
})));
var derniereDate = new Date(Math.max.apply(null, dates.map(function(date) {
    return new Date(date);
})));

  console.log("dates :", dates);
  console.log("Quantités :", quantites);
  console.log("Montants :", montants);

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
