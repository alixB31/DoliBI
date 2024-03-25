
const ctx = document.getElementById('myChart');
// Extrais les données JSON et les stocker dans une variable JavaScript
var donnees = JSON.parse(document.getElementById('donnees').textContent);


// Tableaux pour stocker les dates et les montants par indice
var datesParIndice = {};
var montantsParIndice = {};



// Parcourir toutes les données
for (var indice in donnees) {
    if (donnees.hasOwnProperty(indice)) {
        datesParIndice[indice] = [];
        montantsParIndice[indice] = [];

        var innerObject = donnees[indice];
        for (var key in innerObject) {
            if (innerObject.hasOwnProperty(key)) {
                // Ajouter la date au tableau correspondant à l'indice
                datesParIndice[indice].push(innerObject[key].date);
                // Ajouter le montant au tableau correspondant à l'indice
                montantsParIndice[indice].push(innerObject[key].montant);
            }
        }
    }
}


// Configuration du graphique
var config = {
    type: 'line',
    data: {
        labels: datesParIndice["1"], // Utilisez les dates pour l'indice 1 comme labels de l'axe x
        datasets: []
    },
    options: {
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'day' // Réglez l'unité de temps sur jour si vos données sont quotidiennes
                },
                title: {
                    display: true,
                    text: 'Date'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Montant'
                }
            }
        }
    }
};

// Ajouter les données pour chaque indice
Object.keys(datesParIndice).forEach(function(indice) {
    var dataset = {
        label: 'Montants pour indice ' + indice,
        data: montantsParIndice[indice], // Utilisez les montants pour cet indice comme données de l'axe y
        borderColor: getRandomColor(), // Générer une couleur aléatoire pour chaque indice
        tension: 0.1
    };
    config.data.datasets.push(dataset);
});

// Fonction pour générer une couleur aléatoire
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}


// Afficher les tableaux de dates et de montants par indice
// console.log("Dates par indice :", datesParIndice);
// console.log("Montant par indice :", montantsParIndice);
