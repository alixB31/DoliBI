<?php session_start();?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="static\bootstrap-4.6.2-dist\css\bootstrap.css">
        <link rel="stylesheet" href="static\css\common.css">
        <link rel="stylesheet" href="static\fontawesome-free-6.2.1-web/css/all.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <title>Dashboard</title>
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
        </div>
    </body>
</html>
