<?php
session_start();
require_once __DIR__ . '/bdd.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($pseudo === '' || $email === '' || $password === '') {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO joueur (pseudo, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$pseudo, $email, $hash]);

        header('Location: /login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription joueur</title>
</head>
<body>
        <h1>Créer un compte joueur</h1>
    <form method="POST" action="">
        <label>Pseudo:</label>
        <input type="text" name="pseudo">
        <br><br>
        <label>Email :</label>
        <input type="email" name="email">
        <br><br>
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>