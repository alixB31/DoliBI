<?php 
/** @var mixed $rechercheFournisseur */
/** @var mixed $listeFournisseurs */
/** @var mixed $idChoisis */
/** @var mixed $dateDebut */
/** @var mixed $dateFin */
/** @var mixed $moisOuJour */
/** @var mixed $verifDate */
/** @var mixed $montantEtQuantite */
/** @var mixed $dateFin */
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
                            <li class="rotate-text"><a href="?controller=Stock&action=voirPalmaresFournisseurs" class="active">Palmarès fournisseur</a></li>
                            <li class="rotate-text <?php if (isset($_GET['action']) && $_GET['action'] == 'voirMontantEtQuantiteFournisseurs' || ($_SERVER['REQUEST_METHOD'] == 'POST')) echo 'active'; ?>"><a href="?controller=Stock&action=voirMontantEtQuantiteFournisseurs">Montant et quantité fournisseur</a></li>
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
                <form action="index.php" class="espacementDroite" method="post" id="first-form">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="listeFournisseursLike">
                    Nom du Fournisseur
                    <input name="nom" type="texte" class="espacementDroite form-control" placeholder="Pas obligatoire" value="<?php if($rechercheFournisseur !=null){echo $rechercheFournisseur;}?>">
                    <br/>
                    <button class="espacementDroite form-control btn btn-primary" type="submit">Rechercher fournisseur</button>
                    <br/>
                    <br/>
                </form>
            <div id="second-form">
                <form action="index.php" method="post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="montantEtQuantiteFournisseur">
                    <input type="hidden" name="rechercheFournisseur" value="<?php if($rechercheFournisseur !=null){echo $rechercheFournisseur;}?>">
                    Fournisseur
                    <select id="idFournisseur" class="form-control" name="idFournisseur">
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
                    <input name="dateDebut" type="date" class="form-control" value="<?php if($dateDebut !=null){echo $dateDebut;}?>" required>
                    <br>
                    Date fin
                    <input name="dateFin" type="date" class="form-control" value="<?php if($dateFin !=null){echo $dateFin;}?>" required>
                    <br>
                    Par 
                    <select id="moisOuJour" class="form-control" name="moisOuJour">
                        <option value="mois" <?php if($moisOuJour == "mois") {echo "selected";}?>>mois</option>
                        <option value="jour" <?php if($moisOuJour == "jour") {echo "selected";}?>>jour</option>
                    </select>
                    <br/>
                    <br/>
                    <button class="form-control btn btn-primary" type="submit">Valider</button> 
                </form>
                <?php
                    echo (!$verifDate) ? "<p id='invalide'>Erreur : Les dates ne sont pas cohérentes ou l'écart entre les deux dates est trop éleves.</p>" : "";
                    if($montantEtQuantite != [] && $verifDate) {
                        $donneesJSON = json_encode($montantEtQuantite);
                    
                    
                ?>
                <div class="chart-container" id="graphique">
                    <canvas id="myChart"></canvas>
                </div>
                <span id="donnees" class="invisible"><?php echo $donneesJSON; ?></span>
                <?php
                    } else if($verifDate) {
                        echo "<h3>Il n'y a aucune données pour les dates choisies</h3>";
                    }
                ?>
                <script src="js/scriptFournisseur.js"></script>
                </div>      
            </div>
        </div>
    </body>
</html>