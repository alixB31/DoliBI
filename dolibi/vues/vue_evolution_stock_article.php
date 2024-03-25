<?php session_start();?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="static\bootstrap-4.6.2-dist\css\bootstrap.css">
        <link rel="stylesheet" href="static\css\common.css">
        <link rel="stylesheet" href="static\fontawesome-free-6.2.1-web/css/all.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <title>Gestion stock</title>
    </head>
    <header>
        <div class ="row">
            <div class ="offset-md-2 offset-sm-4 col-4 d-none d-md-block d-sm-block">
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
                    <input type="hidden" name="action" value="listeArticlesLike">
                    Nom de l'article
                    <input name="nom" type="texte" value="<?php if($rechercheArticle !=null){echo $rechercheArticle;}?>">
                    <br>
                    <button type="submit">Rechercher article</button>
                </form>
            </div>
            <div class="row row-gauche">
                <form action="index.php" method= "post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="evolutionStockArticle">
                    <input type="hidden" name="rechercheArticle" value="<?php if($rechercheArticle !=null){echo $rechercheArticle;}?>">
                    Article
                    <select id="idArticle" name="idArticle">
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
                    <input name="dateDebut" type="date" value="<?php if($dateDebut !=null){echo $dateDebut;}?>" required>
                    <br>
                    Date fin
                    <input name="dateFin" type="date" value="<?php if($dateFin !=null){echo $dateFin;}?>" required>
                    <br>
                    Par 
                    <select id="moisOuJour" name="moisOuJour">
                        <option value="mois" <?php if($moisOuJour == "mois") {echo "selected";}?>>mois</option>
                        <option value="jour" <?php if($moisOuJour == "jour") {echo "selected";}?>>jour</option>
                    </select>
                    <button type="submit">Valider</button>
                    <?php
                    if (isset($quantiteAchetes) && isset($quantiteVendues)) {
                        $achetesJSON = json_encode($quantiteAchetes);
                        $venduesJSON = json_encode($quantiteVendues);
                        if ($achetesJSON == 'null' || $venduesJSON == 'null' || $achetesJSON == [[]] || $venduesJSON == [[]]) {
                            echo '<br>';
                            echo '<p id="invalide">Aucune donnée disponible avec les données actuelles</p>';
                        }
                    }
                    ?>
                    <canvas id="myChart"></canvas>
                    <span id="achetes" class="invisible"><?php echo $achetesJSON; ?></span>
                    <span id="vendues" class="invisible"><?php echo $venduesJSON; ?></span>
                    <script src="js/scriptEvolution.js"></script> 
                </form>
            <div>
        </div>
    </body>
</html>