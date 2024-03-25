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
    vendues.push(donneesVendues[i].quantite);
}

new Chart(ctx, {
  type: 'line',
  data: {
    labels: dates,
    datasets: [{
      label: 'Quantité achetés',
      data: achetes,
      yAxisID: 'quantitesAchetes',
      borderColor: 'blue',
      borderWidth: 1
    }, {
      label: 'Quantité vendues',
      data: vendues,
      yAxisID: 'quantiteVendues',
      borderColor: 'red',
      borderWidth: 1
    }],
  },
  options: {
    scales: {
      quantitesAchetes: {
        id: 'quantitesAchetes',
        type: 'linear',
        position: 'left',
        scaleLabel: {
          display: true,
          labelString: 'Quantités'
        }
      }, 
      quantiteVendues: {
        id: 'quantiteVendues',
        type: 'linear',
        position: 'right',
        scaleLabel: {
          display: true,
          labelString: 'Montants'
        }
      }
    }
  }
});
