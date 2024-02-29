<?php
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Page de Connexion</title>
</head>
<body>
    <div class="container centreVertical">
        <div class="cadreUtilisateur connexion">
            <form action="index.php" method="post">
                <img src="festiplan/static/images/logo_blanc.png" alt="Festiplan Logo">
                <br><br>
                <h2 class="grand">Connexion</h2>
                <br>
                <?php
                    echo (!$loginOuMdpOk) ? '<p id="invalide">Erreur : Le mot de passe ou le login est invalide.</p>' : '';
                ?>
                <br>
                
                <input type="hidden" name="controller" value="UtilisateurCompte">
                <input type="hidden" name="action" value="connexion">

                <div class="form-group texteGauche">
                    <div class="input-group">
                        <input name="login" type="text" class="form-control" placeholder="NOM D'UTILISATEUR" required>
                        <div class="input-group-append">
                            <span class="input-group-text"><span class="fas fa-solid fa-user"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group texteGauche">
                    <div class="input-group">
                        <input name="mdp" type="password" class="form-control" placeholder="MOT DE PASSE" required>
                        <div class="input-group-append">
                            <span class="input-group-text"><span class="fas fa-solid fa-lock"></span></span>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="texteCentre">
                    <button type="submit" class="btn btn-primary boutonFleche"><span class="fas fa-arrow-right"></span></button>
                </div>
                <br><br>
            </form>
            <p class="petit">Vous n'avez pas de compte ?  <a class="petit" href="?controller=UtilisateurCompte&action=pageInscription">CREER UN COMPTE</a></p>
        </div>
    </div>
</body>
</html>