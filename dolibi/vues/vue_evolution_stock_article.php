<?php 
/** @var mixed $verifDate */
/** @var mixed $rechercheArticle */
/** @var mixed $listeArticles */
/** @var mixed $idChoisis */
/** @var mixed $dateDebut */
/** @var mixed $dateFin */
/** @var mixed $moisOuJour */
session_start();
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <button name="deconnexion" class="btn btn-deco d-none d-md-block d-sm-block">
                    <i class="fa-solid fa-power-off"></i>
                    <a href="?controller=UtilisateurCompte&action=deconnexion">Déconnexion</a>
                </button>
            </div>
            <div class="col-3">
                <button name="deconnexion" class="btn-deco-rond d-md-none d-sm-none">
                    <a href="?controller=UtilisateurCompte&action=deconnexion"><i class="fa-solid fa-power-off"></i></a>
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
                            <?php if ($_SESSION['droitStock']){ ?>
                                <li class="rotate-text"><a href="?controller=Stock&action=voirPalmaresFournisseurs" class="active">Palmarès fournisseur</a></li>
                                <li class="rotate-text"><a href="?controller=Stock&action=voirMontantEtQuantiteFournisseurs" class="active">Montant et quantité fournisseur</a></li>
                                <li class="rotate-text <?php if ($_GET['action'] == 'voirEvolutionStockArticle' || ($_SERVER['REQUEST_METHOD'] == 'POST')) echo 'active'; ?>"><a href="?controller=Stock&action=voirEvolutionStockArticle">Évolution stock article</a></li>
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
                    <input type="hidden" class="form-control" name="action" value="listeArticlesLike">
                    Nom de l'article
                    <input name="nom" class="form-control" type="texte" value="<?php if($rechercheArticle !=null){echo $rechercheArticle;}?>">
                    <br>
                    <button type="submit">Rechercher article</button>
                </form>
            </div>
            <div class="row row-gauche">
                <form action="index.php" method= "post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="evolutionStockArticle">
                    <input type="hidden" class="form-control" name="rechercheArticle" value="<?php if($rechercheArticle !=null){echo $rechercheArticle;}?>">
                    <br/>
                    Article
                    <select id="idArticle" class="form-control" name="idArticle">
                        <?php
                            foreach ($listeArticles as $liste) {
                                if($liste["id"]==$idChoisis) {
                                    echo "<option value=".$liste["id"]." selected>".$liste["label"]."</option>";
                                } else {
                                    echo "<option value=".$liste["id"].">".$liste["label"]."</option>";
                                } 
                            }
                        ?>
                    </select>
                    <br>
                    Date début
                    <input name="dateDebut" class="form-control" type="date" value="<?php if($dateDebut !=null){echo $dateDebut;}?>" required>
                    <br>
                    Date fin
                    <input name="dateFin" class="form-control" type="date" value="<?php if($dateFin !=null){echo $dateFin;}?>" required>
                    <br>
                    Par 
                    <select id="moisOuJour" class="form-control" name="moisOuJour">
                        <option value="mois" <?php if($moisOuJour == "mois") {echo "selected";}?>>mois</option>
                        <option value="jour" <?php if($moisOuJour == "jour") {echo "selected";}?>>jour</option>
                    </select>
                    <button class="form-control" type="submit">Valider</button>
                    <?php
                    $achetesJSON = null;
                    $venduesJSON = null;
                    if (isset($quantiteAchetes) && isset($quantiteVendues)) {
                        $achetesJSON = json_encode($quantiteAchetes);
                        $venduesJSON = json_encode($quantiteVendues);
                        if ($achetesJSON == 'null' || $venduesJSON == 'null' || $achetesJSON == [[]] || $venduesJSON == [[]]) {
                            echo '<br>';
                            echo '<p id="invalide">Aucune donnée disponible avec les données actuelles</p>';
                        }
                    }
                    ?>
                     <div class="chart-container" id="graphique">
                        <canvas id="myChart"></canvas>
                    </div>
                    <span id="achetes" class="invisible"><?php echo $achetesJSON; ?></span>
                    <span id="vendues" class="invisible"><?php echo $venduesJSON; ?></span>
                    <script src="js/scriptEvolution.js"></script> 
                </form>
            <div>
        </div>
    </body>
</html>