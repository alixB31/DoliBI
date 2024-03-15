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
                    Déconnexion
                </button>
            </div>
    </header>
    <body>
        <div class="container-fluid">
            <div class="row">
                <form action="index.php" method= "post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="listeFournisseursLike">
                    Nom du Fournisseur
                    <input name="nom" type="texte" value="<?php if($rechercheFournisseur !=null){echo $rechercheFournisseur;}?>">
                    <br>
                    <button type="submit">Rechercher fournisseur</button>
                </form>
            </div>
            <div>
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
                    Par mois ou par jour
                    <select id="moisOuJour" name="moisOuJour">
                        <option value="mois">mois</option>
                        <option value="jour">jour</option>
                    </select>
                    <br>
                    <button type="submit">Valider</button> 
                </form>
                <?php
                    $donneesJSON = json_encode($montantEtQuantite);
                ?>
                <div>
                    <canvas id="myChart"></canvas>
                </div>
                <span id="donnees" class="invisible"><?php echo $donneesJSON; ?></span>
                <script src="js/script.js"></script>      
            </div>
        </div>
    </body>
</html>