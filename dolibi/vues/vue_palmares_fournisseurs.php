<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="static\bootstrap-4.6.2-dist\css\bootstrap.css">
        <link rel="stylesheet" href="static\css\common.css">
        <link rel="stylesheet" href="static\fontawesome-free-6.2.1-web/css/all.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
        <title>Gestion stock</title>
    </head>
    <header>
    <div class="row taillerow">
        <div class="col-4 offset-4 d-none d-md-block d-sm-block">
            <h1>Gestion des Stocks</h1>
        </div>
        <div class="col-4 offset-5 d-md-none d-sm-none">
            <h1>Gestion des Stocks</h1>
        </div>
        <div class="col-3">
            <button name="deconnexion" class="btn btn-deco d-none d-md-block d-sm-block">
                <i class="fa-solid fa-power-off"></i>
                <a href="?controller=UtilisateurCompte&action=deconnexion">Déconnexion</a>
            </button>
            <button name="deconnexion" class="btn-deco-rond d-md-none d-sm-none">
                <i class="fa-solid fa-power-off"></i>
                <a href="?controller=UtilisateurCompte&action=deconnexion"></a>
            </button>
        </div>
    </div>
</header>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="menu">
                    <button class="menu-button">Stock</button>
                    <ul class="menu-list">
                        <li class="rotate-text <?php if ($_GET['action'] == 'voirPalmaresFournisseurs' || ($_SERVER['REQUEST_METHOD'] == 'POST')) echo 'active'; ?>"><a href="?controller=Stock&action=voirPalmaresFournisseurs">Palmarès fournisseur</a></li>
                        <li class="rotate-text"><a href="?controller=Stock&action=voirMontantEtQuantiteFournisseurs">Montant et quantité fournisseur</a></li>
                        <li class="rotate-text"><a href="?controller=Stock&action=voirEvolutionStockArticle">Évolution stock article</a></li>
                    </ul>
                    <button class="menu-button">Banque</button>
                    <ul class="menu-list">
                        <li class="rotate-text"><a href="?controller=Banque&action=voirListeSoldesBancaireProgressif">Liste des soldes progressifs d'un ou plusieurs comptes bancaires</a></li>
                        <li class="rotate-text"><a href="?controller=&Banque&action=voirGraphiqueSoldeBancaire">Graphique d'évolution des soldes des comptes bancaires</a></li>
                        <li class="rotate-text"><a href="?controller=&action=">Diagramme sectoriel des comptes bancaires</a></li>
                    </ul>
                </div>
            </div>
            <div class="row row-gauche">
                <form action="index.php" class="espacementDroite col-md-12 col-sm-6 col-4" method="post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="palmaresFournisseurs">
                    Date début
                    <input name="dateDebut" type="date" value="<?php if($dateDebut !=null){echo $dateDebut;}?>" >
                    <br>
                    Date fin
                    <input name="dateFin" type="date" value="<?php if($dateFin !=null){echo $dateFin;}?>" >
                    <br>
                    TOP :
                    <select id="TopX" name="TopX">
                        <option value="5" <?php if($top == "5"){echo "selected";}?> >5</option>
                        <option value="10" <?php if($top == "10"){echo "selected";}?> >10</option>
                        <option value="20" <?php if($top == "20"){echo "selected";}?> >20</option>
                        <option value="30" <?php if($top == "30"){echo "selected";}?> >30</option>
                    </select>
                    <br>
                    <button type="submit">Rechercher</button>
                    <br>
                    <table class="table table-striped text-center table-bordered">
                        <tr>
                            <th>Code Fournisseur</th>
                            <th>Nom Fournisseur</th>
                            <th>Montant facture HT</th>
                            <th>Montant commande HT</th>
                        </tr>
                        <?php
                            $compteur = 0;
                            foreach ($palmares as $element) {
                                // Affiche le nombre de fournisseurs choisis par l'utilisateur
                                if ($compteur <= $top) {
                                    echo "<tr>
                                            <td>".$element['code_fournisseur']."</td>
                                            <td>".$element['nom']."</td>
                                            <td>".$element['prixHT_Facture']."</td>
                                            <td>".$element['prixHT_Commande']."</td>
                                        </tr>";
                                    $compteur++;
                                }
                            }
                        ?>
                    </table>
                    <?php
                        if($palmares==[]) {
                            echo "Aucune données ne correspond à vos paramètres";
                        }
                    ?>
                </form>
            </div>
        </div>
    </body>
</html>