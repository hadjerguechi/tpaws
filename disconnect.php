<?php
    session_start(); // Démarrage de la session
    session_destroy(); // Destruction de la session 
    session_unset(); // Destruction des variables de session 
    header("Location: login.php"); // Redirection vers 'login.php'
?>