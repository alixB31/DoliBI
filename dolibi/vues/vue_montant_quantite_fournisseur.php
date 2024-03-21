<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="static\bootstrap-4.6.2-dist\css\bootstrap.css">
        <link rel="stylesheet" href="static\css\common.css">
        <link rel="stylesheet" href="static\fontawesome-free-6.2.1-web/css/all.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <li class="rotate-text"><a href="?controller=Stock&action=voirPalmaresFournisseurs">Palmarès fournisseur</a></li>
                        <li class="rotate-text <?php if ($_GET['action'] == 'voirMontantEtQuantiteFournisseurs' || ($_SERVER['REQUEST_METHOD'] == 'POST')) echo 'active'; ?>"><a href="?controller=Stock&action=voirMontantEtQuantiteFournisseurs">Montant et quantité fournisseur</a></li>
                        <li class="rotate-text?>"><a href="?controller=Stock&action=voirEvolutionStockArticle">Évolution stock article</a></li>
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
                <form action="index.php" method= "post" id="first-form">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="listeFournisseursLike">
                    Nom du Fournisseur
                    <input name="nom" type="texte" id="test" value="<?php if($rechercheFournisseur !=null){echo $rechercheFournisseur;}?>">
                    <br>
                    <button type="submit">Rechercher fournisseur</button>
                </form>
            <div id="second-form">
                <form action="index.php" method= "post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="montantEtQuantiteFournisseur">
                    <input type="hidden" name="rechercheFournisseur" value="<?php if($rechercheFournisseur !=null){echo $rechercheFournisseur;}?>">
                    Fournisseur
                    <select id="idFournisseur" name="idFournisseur">
                        <?php
                            foreach ($listeFournisseurs as $liste) {
                                if($liste["id_fournisseur"]==$idChoisis) {
                                    echo "<option value=".$liste["id_fournisseur"]." selected>".$liste["nom"]."</option>";
                                } else {
                                    echo "<option value=".$liste["id_fournisseur"].">".$liste["nom"]."</option>";
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
                    <br>
                    <button type="submit">Valider</button> 
                </form>
                <?php
                    if($montantEtQuantite != []) {
                        $donneesJSON = json_encode($montantEtQuantite);
                    
                    
                ?>

                        <canvas id="myChart"></canvas>
                        <span id="donnees" class="invisible"><?php echo $donneesJSON; ?></span>
                <?php
                    } else  {
                        echo "<h3>Il n'y a aucune données pour les dates choisis</h3>";
                    }
                ?>
                <script src="js/scriptFournisseur.js"></script>
                </div>      
            </div>
        </div>
    </body>
</html>