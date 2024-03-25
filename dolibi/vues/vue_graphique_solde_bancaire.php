<?php session_start();?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="static\bootstrap-4.6.2-dist\css\bootstrap.css">
        <link rel="stylesheet" href="static\css\common.css">
        <link rel="stylesheet" href="static\fontawesome-free-6.2.1-web/css/all.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
        <title>Gestion Banque</title>
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
                            <li class="rotate-text <?php if ($_GET['action'] == 'voirGraphiqueSoldeBancaire' || ($_SERVER['REQUEST_METHOD'] == 'POST')) echo 'active'; ?>"><a href="?controller=Banque&action=voirGraphiqueSoldeBancaire">Graphique d'évolution des soldes des comptes bancaires</a></li>
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
                    <input type="hidden" name="controller" value="Banque">
                    <input type="hidden" name="action" value="graphiqueEvolution">
                    Banque
                    <?php 
                        foreach($listeBanques as $banque) {
                    ?>
                        <div>
                            <input type="checkbox" class="checkBoxs" name="Banque[]" value="<?php echo $banque["id_banque"]; ?>" <?php
                            // Vérifier si l'utilisateur est dans la liste des organisateurs
                            if (in_array($banque['id_banque'], $banques)) {
                                echo 'checked';
                                    
                            }
                            ?>>
                            <?php echo $banque["nom"]; ?>
                            <br>
                        </div>
                    <?php
                        }
                    ?>
                    <br>
                    <select id="histoOuCourbe" name="histoOuCourbe">
                        <option value="histo" <?php if($histoOuCourbe == "histo") {echo "selected";}?>>histogramme</option>
                        <option value="courbe" <?php if($histoOuCourbe == "courbe") {echo "selected";}?>>courbe</option>
                    </select>
                    <br>
                    Saissisez une année :
                    <input type="number" name="an" value="<?php if($an !=null){echo $an;}?>" required/>
                    <br>
                    <select name="mois" id="mois">
                        <option value="tous" <?php if($mois == "tous") {echo "selected";}?> >Tous les mois</option>
                        <option value="1" <?php if($mois == "1") {echo "selected";}?>>Janvier</option>
                        <option value="2" <?php if($mois == "2") {echo "selected";}?>>Février</option>
                        <option value="3" <?php if($mois == "3") {echo "selected";}?>>Mars</option>
                        <option value="4" <?php if($mois == "4") {echo "selected";}?>>Avril</option>
                        <option value="5" <?php if($mois == "5") {echo "selected";}?>>Mai</option>
                        <option value="6" <?php if($mois == "6") {echo "selected";}?>>Juin</option>
                        <option value="7" <?php if($mois == "7") {echo "selected";}?>>Juillet</option>
                        <option value="8" <?php if($mois == "8") {echo "selected";}?>>Août</option>
                        <option value="9" <?php if($mois == "9") {echo "selected";}?>>Septembre</option>
                        <option value="10" <?php if($mois == "10") {echo "selected";}?>>Octobre</option>
                        <option value="11" <?php if($mois == "11") {echo "selected";}?>>Novembre</option>
                        <option value="12" <?php if($mois == "12") {echo "selected";}?>>decembre</option>
                    </select>
                    
                    <br>
                    <button type="submit">Rechercher</button>
                    <br>
                </form>
            </div>
            <div>
                <canvas id="myChart"></canvas>
                <?php $donneesJSON = json_encode($listeValeurs); ?>
                <span id="donnees" class="invisible"><?php echo $donneesJSON; ?></span>
                <script src="js/scriptGraphiqueBancaire.js"></script>
            </div>
        </div>
    </body>
</html>