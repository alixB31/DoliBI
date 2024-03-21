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
            <div class="row row-gauche">
                <form action="index.php" method= "post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="listeArticlesLike">
                    Nom de l'article
                    <input name="nom" type="texte" value="<?php if($rechercheArticle !=null){echo $rechercheArticle;}?>">
                    <br>
                    <button type="submit">Rechercher fournisseur</button>
                </form>
            </div>
            <div>
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
                    <input name="dateDebut" type="date" value="<?php if($dateDebut !=null){echo $dateDebut;}?>" >
                    <br>
                    Date fin
                    <input name="dateFin" type="date" value="<?php if($dateFin !=null){echo $dateFin;}?>" >
                    <br>
                    Par 
                    <select id="moisOuJour" name="moisOuJour">
                        <option value="mois" <?php if($moisOuJour == "mois") {echo "selected";}?>>mois</option>
                        <option value="jour" <?php if($moisOuJour == "jour") {echo "selected";}?>>jour</option>
                    </select>
                    <button type="submit">Valider</button>
                    <?php
                        $achetesJSON = json_encode($quantiteAchetes);
                        $venduesJSON = json_encode($quantiteVendues);
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