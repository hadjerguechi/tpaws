<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Social Internetwork</title>
</head>
<body>
  

    <?php
        // Connexion à la BDD
        include "./database.php";
        $pdo = Database::connect();
        session_start(); // Démarrage de la session pour avoir accès à '$_SESSION'
        if($_POST) { // Si le formulaire HTML a été soumis, 
            if(strlen($_POST["username"]) > 5 && strlen($_POST["username"]) < 30 && strlen($_POST["password"]) > 5 && strlen($_POST["password"]) < 30) { // Validation de la taille du username et du password 
                // Récupération (s'il existe) de l'utilisateur correspond au 'username' saisi 
                $req = $pdo->query("SELECT * FROM `user` WHERE username='{$_POST['username']}'");
                $user = $req->fetch();
                
                // Si l'utilisateur existe et que son mot de passe correspond à celui enregistré en BDD,
                if ($user && password_verify($_POST["password"], $user["password"])) {
                    $_SESSION["username"] = $_POST["username"]; // On connecte l'utilisateur en passant son username dans la session
                    header("Location: index.php"); // Redirection vers 'index.php'
                } else { // Affichage des messages d'erreurs associées aux validations 
                    echo "<p class='m-3' style='color:red'>Vos identifiants sont incorrects...</p>";
                }
            } else {
                echo "<p class='m-3' style='color:red'>Vos identifiants doivent être compris entre 5 et 30 caractères</p>";
            }
        }
        Database::disconnect(); // Déconnexion de la BDD
    ?>

    <div class="container mt-3">
        <div class="row justify-content-center align-items-center mt-5">
           <div class=" col-md-8 mt-5">
            <h3>Connectez-vous !</h3>
                <form method="post" class="mb-5">
                    <div class="form-group">
                        <label for="username">Identifiant</label>
                        <input type="text" class="form-control" name="username" placeholder="Votre identifiant" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" placeholder="Votre mot de passe" minlength="5" maxlength="30" name="password" required>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Se connecter">
                </form>
                <a href="./createAccount.php" class="mt-4">Créer un compte</a>
           </div>
        </div>





    </div>
</body>
</html>