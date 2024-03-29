// Tableau des couleurs prédéfinies
const colors = ['#FF5733', '#33FF57', '#5733FF', '#FFFF33', '#33FFFF', '#FF33FF', '#FF5733', '#33FF57', '#5733FF', '#FFFF33']; // Ajoutez autant de couleurs que nécessaire

// Extraire les données JSON et les stocker dans une variable JavaScript
var donnees = JSON.parse(document.getElementById('donnees').textContent);

// Extraire les données
var banque = [];
var solde = [];
var backgroundColors = [];
var borderColors = [];

// Associer chaque banque à une couleur prédéfinie
for (var i = 0; i < donnees.length; i++) {
    if (donnees[i].solde > 0) {
        banque.push(donnees[i].banque);
        solde.push(donnees[i].solde);
    }
    backgroundColors.push(colors[i % colors.length]); // Utiliser les couleurs du tableau en boucle
    borderColors.push(colors[i % colors.length]); // Utiliser les couleurs du tableau en boucle
}

// Récupérer le canvas pour le graphique
const ctx = document.getElementById('myChart');

// Créer le graphique une fois que toutes les données sont prêtes
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: banque,
        datasets: [{
            label: 'Solde',
            data: solde,
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
