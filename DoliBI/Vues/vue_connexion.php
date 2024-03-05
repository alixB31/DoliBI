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
            <form action="index.php" method="post">
                <input type="hidden" name="controller" value="UtilisateurCompte">
                <input type="hidden" name="action" value="connexion">
                    <div class="cadre mx-auto offset-md-3 col-md-4 col-12">
                        <input type ="checkbox" name="coIUT">
                        Connexion depuis l'IUT ? 
                        <br/>
                        URL de connexion :
                        <br/>
                        <input type="URLco" class="form-control" name="url" placeholder="Entrez l'URL de connexion"/>
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
        <footer>
            <div class="container-fluid">
                <div class="row">
                    <br/>
                    Réalisé par :
                    <div class="col-6 contenue_droite">
                        <img src="static/images/logo-iut.png" width="150" height="90" class="logo" id="logoIUT" alt="Logo IUT" href="http://www.iut-rodez.fr"
                            target="_blank" />
                    </div>
                    <br>
                </div>
            </div>
        </footer>
    </body>
</html>
