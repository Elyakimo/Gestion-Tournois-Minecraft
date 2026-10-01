<?php
session_start();
$estConnecte = isset($_SESSION['id_joueur']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MSIO</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button><a href="/classement.php">Classement</a></button>
    <button><a href="/signin.php">Connexion</a></button>
    <button><a href="/tournoi.php">Tournois</a></button>
    <button><a href="/equipe.php">Équipe</a></button>
</body>
</html>