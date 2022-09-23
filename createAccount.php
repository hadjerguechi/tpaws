<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Tp aws</title>
</head>
<body>
  

    <?php
        // Connexion à la BDD
        require "./database.php";
        $pdo = Database::connect();
    
        session_start(); // Démarrage de la session pour avoir accès à '$_SESSION'
        if($_POST) { // Si le formulaire HTML a été soumis, 
            
            if(strlen($_POST["username"]) > 5 && strlen($_POST["username"]) < 30 && strlen($_POST["password"]) > 5 && strlen($_POST["password"]) < 30) { // Validation de la taille du username et du password 
                // Récupération (s'il existe) de l'utilisateur correspond au 'username' saisi 
                $req = $pdo->query("SELECT username FROM `user` WHERE username='{$_POST['username']}'");
                $user = $req->fetch();

                if($user) { // Si l'utilisateur existe déjà en BDD, affichage d'un message d'erreur
                    echo "<p class='m-3' style='color:red'>Cet identifiant est déjà utilisé</p>";
                } else { // Sinon,
                    // Requête SQL permettant l'insertion de l'utilisateur en BDD
                    $req = $pdo->prepare("INSERT INTO `user` (username, password) VALUES (:username, :password)");
                    $req->execute([
                        "username" => $_POST["username"],
                        "password" => password_hash($_POST["password"], PASSWORD_DEFAULT)
                    ]);
                    $_SESSION["username"] = $_POST["username"]; // On connecte l'utilisateur en passant son username dans la session
                    header("Location: index.php"); // Redirection vers 'index.php'
                }
            } else { // Affichage du message d'erreur associé à la validation
                echo "<p class='m-3' style='color:red'>Vos identifiants doivent être compris entre 5 et 30 caractères</p>";
            }
        }
        Database::disconnect(); // Déconnexion de la BDD
    ?>

    <div class="container mt-3">
        <h3>Créer un compte utilisateur</h3>
        <form method="post">
            <div class="form-group">
                <label for="username">Identifiant</label>
                <input type="text" class="form-control" placeholder="Votre identifiant"  name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" placeholder="Votre mot de passe"  minlength="5" maxlength="30" name="password" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Créer un compte">
        </form>
        <a href="./login.php">Se connecter</a>
    </div>
</body>
</html>