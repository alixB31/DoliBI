<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="static\bootstrap-4.6.2-dist\css\bootstrap.css">
        <link rel="stylesheet" href="static\css\common.css">
        <link rel="stylesheet" href="static\fontawesome-free-6.2.1-web/css/all.css">
        <title>Gestion stock</title>
    </head>
    <header>
        <div class ="row">
            <div class ="col-6">
                <hspan class="titre">Doli-BI</hspan>
            </div>
            <div class="col-4">
                Gestion des Stocks
            </div>
            <div class="col-2">
                <button name="deconnexion" class="btn-deco d-none d-md-block d-sm-block">
                    <i class="fa-solid fa-power-off"></i>
                    Déconnexion
                </button>
            </div>
        </div>
    </header>
    <body>
        <div class="container-fluid">
            <div class="row">
                <form action="index.php" method= "post">
                    <input type="hidden" name="controller" value="Stock">
                    <input type="hidden" name="action" value="palmaresFournisseurs">
                    Date début
                    <input name="dateDebut" type="date">
                    <br>
                    Date fin
                    <input name="dateFin" type="date">
                    <br>
                    <select id="TopX" name="TopX">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
                    <br>
                    <button type="submit">Test var_dump</button>
                    <br>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Nom Fournisseur</th>
                            <th>Montant achetés HT</th>
                            <th>Code Fournisseur</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
</html>