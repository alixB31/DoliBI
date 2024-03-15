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
                    <input type="hidden" name="action" value="palmaresFournisseurs">
                    Date début
                    <input name="dateDebut" type="date" value="<?php if($dateDebut !=null){echo $dateDebut;}?>" >
                    <br>
                    Date fin
                    <input name="dateFin" type="date" value="<?php if($dateFin !=null){echo $dateFin;}?>" >
                    <br>
                    TOP :
                    <select id="TopX" name="TopX">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
                    <br>
                    <button type="submit">Rechercher</button>
                    <br>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Code Fournisseur</th>
                            <th>Nom Fournisseur</th>
                            <th>Montant achetés HT</th>
                        </tr>
                        <?php
                            $compteur = 0;
                            foreach ($palmares as $element) {
                                // Affiche le nombre de fournisseurs choisis par l'utilisateur
                                if ($compteur <= $top) {
                                    echo "<tr>
                                            <td>".$element['code_fournisseur']."</td>
                                            <td>".$element['nom']."</td>
                                            <td>".$element['prixHT']."</td>
                                        </tr>";
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