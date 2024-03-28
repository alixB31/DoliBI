<?php session_start();
if (!isset($_SESSION['droitBanque']) || $_SESSION['droitBanque'] == false) {
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
                            <li class="rotate-text <?php if ($_GET['action'] == 'voirListeSoldesBancaireProgressif' || ($_SERVER['REQUEST_METHOD'] == 'POST')) echo 'active'; ?>"><a href="?controller=Banque&action=voirListeSoldesBancaireProgressif">Liste des soldes progressifs d'un ou plusieurs comptes bancaires</a></li>
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
                    <input type="hidden" name="controller" value="Banque">
                    <input type="hidden" name="action" value="listeSoldesBancaireProgressif">
                    Banque :
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
                    Date début
                    <input name="dateDebut" class="form-control" type="date" value="<?php if($dateDebut !=null){echo $dateDebut;}?>" required>
                    <br>
                    Date fin
                    <input name="dateFin" class="form-control" type="date" value="<?php if($dateFin !=null){echo $dateFin;}?>" required>
                    <br>
                    Total par
                    <select id="moisOuJour" class="form-control" name="moisOuJour">
                        <option value="mois" <?php if($moisOuJour == "mois") {echo "selected";}?>>mois</option>
                        <option value="jour" <?php if($moisOuJour == "jour") {echo "selected";}?>>jour</option>
                    </select>
                    <br>
                    <br>
                    <button class="form-control" type="submit">Rechercher</button>
                    <br>
                </form>
            </div>
            <?php
                foreach ($banquesCoches as $banque) {
                    echo '<h3 class="row-gauche">'.$banque['nom'].'</h3>';
            ?>
                    <div class="row row-gauche">
                        <table class="table table-striped table-bordered table-responsive">
                            <tr>
                                <th>Date</th>
                                <th>Total</th>
                            </tr>
                            <?php
                                foreach ($listeEcritures[$banque['id_banque']] as $ecriture) {
                                    
                                    // Affiche le nombre de fournisseurs choisis par l'utilisateur
                                    
                                    echo "<tr>
                                            <td>".$ecriture['date']."</td>";
                                    ?>
                                            <td 
                                            <?php 
                                                if($ecriture['montant']>0) { 
                                                    echo "class='benef'";
                                                } else if($ecriture['montant']<0) {
                                                    echo "class='perte'";
                                                } 
                                            ?>
                                                >
                                            <?php
                                                echo $ecriture['montant'];
                                            ?>
                                            </td>
                                        </tr>
                            <?php
                                }
                            ?>
                        </table>
                    </div>
            <?php
                }
            ?>
        </div>
    </body>
</html>