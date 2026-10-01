<?php
session_start();
require_once __DIR__ . '/bdd.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['pseudo'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM joueur WHERE pseudo = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['id_joueur'] = $user['id_joueur'];
        header('Location: /index.php');
        exit;
    } else {
        $error = 'Erreur dans la saisie, merci de vérifier votre pseudo et mot de passe.';
    }
}

?>

<h1>Connexion</h1>
 
<?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>
 
<form method="POST" action="/login.php">
    <label>pseudo</label>
    <input type="text" name="pseudo" required>
    <label>Mot de passe</label>
    <input type="password" name="password" required>
    <button type="submit">Se connecter</button>
    <p class="muted"><a href="/mot_depasseoublié.php"> Mot de passe oublié ?</a></p>
</form>
<p class="muted">Pas de compte ? <a href="/signup.php">Inscrivez-vous</a>.</p>