<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="static\bootstrap-4.6.2-dist\css\bootstrap.css">
        <link rel="stylesheet" href="static\css\common.css">
        <link rel="stylesheet" href="static\fontawesome-free-6.2.1-web/css/all.css">
        <title>Connexion</title>
    </head>
    <header>
        <hspan class="titre">Doli-BI</hspan>
    </header>
    <body>
        <div class="fond_degrade_noir">
            <div class="col-12 text-center">
                <h1>Bienvenue sur Doli-BI</h1>
                <h2>Veuillez vous identifier pour continuer</h2>
            </div>
            <div class="cadre mx-auto offset-md-3 col-md-4 col-12">
                <form action="index.php" method="post">
            
                    <input type="hidden" name="controller" value="UtilisateurCompte">
                    <input type="hidden" name="action" value="ajoutURL">
                    Ajouter un url de connexion
                    <input type="text" class="form-control" name="urlSaisi" placeholder="URL de connexion"/>
                    <input class="btn-blanc btn-modif" type="submit" value="Ajouter">

                </form>
                <form action="index.php" method="post">
                    <input type="hidden" name="controller" value="UtilisateurCompte">
                    <input type="hidden" name="action" value="connexion">
                    
                        <br/>
                        
                        URL de connexion :
                        <select name="urlExistant" id="urlExistant">
                            <?php
                           foreach ($listeUrl as $Url) {
                                echo "<option value=".$Url.">".$Url."</option>";
                           }
                            ?>
                        </select>
                        <br/>
                        Identifiant :
                        <br/>
                        <input type="text" class="form-control" name="identifiant" placeholder="Entrez votre identifiant">
                        Mot de passe :
                        <br/>
                        <input type="password" class="form-control" name="mdp" placeholder="Entrez votre mot de passe"/>
                    </div>
                    <br />
                    <div class="col-12 text-center">
                        <input class="btn-blanc btn-modif" type="submit" value="Se connecter">
                    </div>
            </form>
        </div>
        <br/>
        <br>
            Réalisé par :
            <div class="col-6 contenue_droite">
                <img src="static/images/logo-iut.png" width="150" height="90" class="logo" id="logoIUT" alt="Logo IUT" href="http://www.iut-rodez.fr"
                    target="_blank"/>
            </div>
    </body>
</html>
