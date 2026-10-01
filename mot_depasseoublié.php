<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
</head>
<body>
        <h1>Réinitialiser le mot de passe</h1>
        <p><strong>Pour réinitialiser votre mot de passe, saisissez votre adresse e-mail afin de recevoir un code d'autorisation.</strong></p>
    <form method="POST" action="traitement.php">
        <label>Email :</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>