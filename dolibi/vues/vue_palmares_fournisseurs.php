    <?php 
/** @var mixed $verifDate */
/** @var mixed $dateFin */
/** @var mixed $dateDebut */
/** @var mixed $top */
/** @var mixed $palmares */
if (!isset($_SESSION['droitStock']) || $_SESSION['droitStock'] == false) {
    header("Location: ../dolibi/index.php");
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="static\bootstrap-4.6.2-dist\css\bootstrap.css">
        <link rel="stylesheet" href="static\css\common.css">
        <link rel="stylesheet" href="static\fontawesome-free-6.2.1-web/css/all.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
        <title>Gestion Stock</title>
    </head>
    <header>
        <div class ="row">
            <div class ="offset-md-3 offset-sm-4 col-4 d-none d-md-block d-sm-block">
                <hspan class="titre">Doli-BI</hspan>
            </div>
            <div class ="offset-5 col-4 d-md-none d-sm-none">
                <hspan class="titre">Doli-BI</hspan>
            </div> 
            <div class="offset-md-2 offset-sm-1 col-md-2 col-sm-2 d-none d-md-block d-sm-block  ">
                <a href="?controller=UtilisateurCompte&action=deconnexion">
                    <button name="deconnexion" class="btn btn-deco d-none d-md-block d-sm-block">
                        <i class="fa-solid fa-power-off"></i>
                        Déconnexion
                    </button>
                </a>
            </div>
            <div class="col-3">
                <a href="?controller=UtilisateurCompte&action=deconnexion">
                    <button name="deconnexion" class="btn-deco-rond d-md-none d-sm-none">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </a>
            </div>
        </div>
    </header>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="menu">
                    <button class="menu-button">Stock</button>
                    <ul class="menu-list">
                        <?php if ($_SESSION['droitStock']){ ?>
                            <li class="rotate-text <?php if (isset($_GET['action']) && $_GET['action'] == 'voirPalmaresFournisseurs' || ($_SERVER['REQUEST_METHOD'] == 'POST')) echo 'active'; ?>"><a href="?controller=Stock&action=voirPalmaresFournisseurs">Palmarès fournisseur</a></li>
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
                
                <form action="index.php" method= "post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="palmaresFournisseurs">
                    Date début
                    <input name="dateDebut" type="date" class="form-control" value="<?php if($dateDebut !=null){echo $dateDebut;}?>" required>
                    <br>
                    Date fin
                    <input name="dateFin" type="date" class="form-control" value="<?php if($dateFin !=null){echo $dateFin;}?>" required>
                    <br>
                    TOP :
                    <select id="TopX" class="form-control" name="TopX">
                        <option value="5" <?php if($top == "5"){echo "selected";}?> >5</option>
                        <option value="10" <?php if($top == "10"){echo "selected";}?> >10</option>
                        <option value="20" <?php if($top == "20"){echo "selected";}?> >20</option>
                        <option value="30" <?php if($top == "30"){echo "selected";}?> >30</option>
                    </select>
                    <br>
                    <button class="form-control btn btn-primary"  type="submit">Rechercher</button>
                    <br>
                    <br>
                    <?php
                    echo (!$verifDate) ? '<p id="invalide">Erreur : Les dates ne sont pas cohérentes.</p>' : '';
                    ?>
                    <table class="table table-striped text-center table-bordered table-responsive">
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
                                if ($compteur < $top) {
                                    echo '<tr>
                                            <td>'.$element['code_fournisseur'].'</td>
                                            <td>'.$element['nom'].'</td>
                                            <td class="texte-droite">'.number_format(floatval($element['prixHT_Facture']),2).'</td>
                                            <td class="texte-droite">'.number_format(floatval($element['prixHT_Commande']),2).'</td>
                                        </tr>';
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