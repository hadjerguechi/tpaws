<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>TP aws</title>
</head>
<body>
  

    <?php
        // Connexion à la BDD
        include "./database.php";
        $pdo = Database::connect();
        session_start(); // Démarrage de la session pour avoir accès à '$_SESSION'
    ?>

    <div class="container mt-5">
        <?php if(key_exists("username", $_SESSION)) { /* Si l'utilisateur est connecté */ ?>
            <h3>Bienvenue <?= $_SESSION["username"] ?></h3>
            <div class="row">
              <div class="col-md-8 mb-5">
              <a href="./disconnect.php" class="btn btn-danger">Se déconnecter</a>
              </div>
                

                <div class="col-md-8 mt-5">
                    Type de services:
                <form action="post">
                    <label class="form-control">
                        <input type="radio" name="radio" />
                        Création de site Web statique
                    </label>

                    <label class="form-control">
                        <input type="radio" name="radio" checked />
                        Création de site Web dynamique
                    </label>
                </form>
                </div>
            </div>
        <?php } else {
                echo '<a href="./login.php" class="btn btn-primary">Se connecter</a>';
            }
            echo '<div class="d-flex mt-3">';
            // Parcours de tous les posts enregistrés en BDD
            foreach ($pdo->query("SELECT * FROM `post` ORDER BY id DESC") as $rowPost) {
                // Récupération de l'utilisateur associé au post
                $req = $pdo->query("SELECT * FROM `user` WHERE id={$rowPost['id_user']}");
                $user = $req->fetch();

                // Récupération de l'image associée au post
                $req = $pdo->query("SELECT * FROM `image` WHERE id={$rowPost['id_image']}");
                $image = $req->fetch();

                // Convertit la date enregistrée en BDD en type PHP 'DateTime'
                $date = date_create($rowPost["created_at"]);
        ?>
                <div class="card ml-3" style="width: 18rem;">
                    <img class="card-img-top" src="<?= $image["path"] ?>" alt="Card image cap">
                    <div class="card-body">
                        <p class="card-text"><?= $rowPost['content'] ?></p>
                        <footer class="blockquote-footer"><?= $user["username"] ?>, le <?= date_format($date, "d/m/Y") ?> à <?= date_format($date, "H:i") ?></footer><br>
                        <a href="./readPost.php?id=<?= $rowPost["id"] ?>" title="Voir" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                        <?php if(key_exists("username", $_SESSION)) { // Si l'utilisateur est connecté
                                if($user["username"] === $_SESSION["username"]) { /* Si l'utilisateur est l'auteur du post */
                                    // Récupération de l'utilisateur connecté 
                                    $req = $pdo->query("SELECT id FROM `user` WHERE username='{$_SESSION['username']}'");
                                    $connectedUser = $req->fetch();

                                    // Récupération du like associé au post
                                    $req = $pdo->query("SELECT * FROM `liked_post` WHERE id_post={$rowPost['id']} && id_user={$connectedUser['id']}");
                                    $likedPost = $req->fetch();
                                ?>
                                    <a href="./updatePost.php?id=<?= $rowPost["id"] ?>" title="Modifier" class="btn btn-secondary"><i class="fas fa-pen"></i></a>
                                    <a href="./deletePost.php?id=<?= $rowPost["id"] ?>" title="Supprimer" class="btn btn-warning"><i class="fas fa-trash"></i></a>
                               <?php } ?>
                                    <a style="float: right;" href="./likedPost.php?id=<?= $rowPost["id"] ?>" title="Aimer"><i class="<?= $likedPost ? 'fas' : 'far' ?> fa-thumbs-up fa-2x"></i></a>
                        <?php } ?>
                    </div>
                </div>
           <?php
                }
                Database::disconnect(); // Déconnexion de la BDD
            ?>
        </div>
    </div>
</body>
</html>