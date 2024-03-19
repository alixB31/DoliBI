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
            <?php
                    echo (!$loginOuMdpOk) ? '<p id="invalide">Erreur : Identifiant ou mot de passe invalide.</p>' : '';
                ?>
            <div class="corps-principal">    
                <form action="index.php" method="post" id="formulaireAjoutURL">
                    <input type="hidden" name="controller" value="UtilisateurCompte">
                    <input type="hidden" name="action" value="ajoutURL">
                    Ajouter un url de connexion :
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
                        <button id="btnAjouterURL">+</button><a href="#" id="btnSupprimerURL"><i class="fa-solid fa-trash"></i></a>
                        
                        <br/>
                        Identifiant :
                        <br/>
                        <input type="text" class="form-control" name="identifiant" placeholder="Entrez votre identifiant">
                        Mot de passe :
                        <br/>
                        <input type="password" class="form-control" name="mdp" placeholder="Entrez votre mot de passe"/>
                        <input class="btn-blanc btn-modif" type="submit" value="Se connecter">
                    </div>
                    <br/>
                </form>
            </div>    
        </div>
        <script src="js/scriptConnexion.js"></script>
    </body>
</html>
