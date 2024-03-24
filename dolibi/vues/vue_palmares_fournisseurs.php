<?php session_start();?>
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
        <div class ="row">
            <div class ="col-4">
                <hspan class="titre">Doli-BI</hspan>
            </div>
            <div class="col-4">
            <a href="?controller=Stock&action=voirDashboard"><h1>Gestion des Stocks</h1></a>
            </div>
            <div class="col-3">
            <button name="deconnexion" class="btn-deco d-none d-md-block d-sm-block">
                    <i class="fa-solid fa-power-off"></i>
                    <a href="?controller=UtilisateurCompte&action=deconnexion">Déconnexion<a>
                </button>
            </div>
    </header>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="menu">
                    <button class="menu-button">Stock</button>
                    <ul class="menu-list">
                        <?php if ($_SESSION['droitStock']){ ?>
                            <li class="rotate-text <?php if ($_GET['action'] == 'voirPalmaresFournisseurs' || ($_SERVER['REQUEST_METHOD'] == 'POST')) echo 'active'; ?>"><a href="?controller=Stock&action=voirPalmaresFournisseurs">Palmarès fournisseur</a></li>
                            <li class="rotate-text"><a href="?controller=Stock&action=voirMontantEtQuantiteFournisseurs" class="active">Montant et quantité fournisseur</a></li>
                            <li class="rotate-text"><a href="?controller=Stock&action=voirEvolutionStockArticle" class="active">Évolution stock article</a></li>
                        <?php }else { ?>
                            <li class="rotate-text">Palmarès fournisseur</li>
                            <li class="rotate-text">Montant et quantité fournisseur</li>
                            <li class="rotate-text">Évolution stock article</li>
                        <?php } ?>
                        </ul>
                        <button class="menu-button">Banque</button>
                        <ul class="menu-list">
                        <?php if ($_SESSION['droitBanque']){ ?>
                            <li class="rotate-text"><a href="?controller=Banque&action=voirListeSoldesBancaireProgressif" class="active">Liste des soldes progressifs d'un ou plusieurs comptes bancaires</a></li>
                            <li class="rotate-text"><a href="?controller=Banque&action=voirGraphiqueSoldeBancaire" class="active">Graphique d'évolution des soldes des comptes bancaires</a></li>
                            <li class="rotate-text"><a href="?controller=Banque&action=voirDiagrammeRepartition">Diagramme sectoriel des comptes bancaires</a></li>
                        <?php }else { ?>
                            <li class="rotate-text">Liste des soldes progressifs d'un ou plusieurs comptes bancaires</li>
                            <li class="rotate-text">Graphique d'évolution des soldes des comptes bancaires</li>
                            <li class="rotate-text">Diagramme sectoriel des comptes bancaires</li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="row row-gauche">
                <?php
                    echo (!$verifDate) ? '<p id="invalide">Erreur : Les dates ne sont pas cohérentes.</p>' : '';
                ?>
                <form action="index.php" method= "post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="palmaresFournisseurs">
                    Date début
                    <input name="dateDebut" type="date" value="<?php if($dateDebut !=null){echo $dateDebut;}?>" required>
                    <br>
                    Date fin
                    <input name="dateFin" type="date" value="<?php if($dateFin !=null){echo $dateFin;}?>" required>
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
                    <table class="table table-striped table-bordered">
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