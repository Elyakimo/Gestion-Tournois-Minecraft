<?php
require_once __DIR__ . '/bdd.php';

$erreur = '';

// 1. Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $tag = trim($_POST['tag'] ?? '');

    if ($nom === '') {
        $erreur = 'Le nom de l\'équipe est obligatoire.';
    } elseif (mb_strlen($tag) > 10) {
        $erreur = 'Le tag ne doit pas dépasser 10 caractères.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO equipe (nom_equipe, tag) VALUES (:nom, :tag)');
            $stmt->execute([
                'nom' => $nom,
                'tag' => $tag !== '' ? $tag : null, // tag facultatif
            ]);

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23505') {
                $erreur = 'Cette équipe existe déjà.';
            } else {
                $erreur = 'Erreur lors de l\'ajout de l\'équipe.';
            }
        }
    }
}

// 2. Récupération de la liste
$stmt = $pdo->query('SELECT id_equipe, nom_equipe, tag FROM equipe ORDER BY nom_equipe');
$equipes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Équipes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ajouter une équipe</h1>

    <?php if ($erreur): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nom">Nom de l'équipe :</label>
        <input type="text" id="nom" name="nom" maxlength="255" required
               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        <br>
        <label for="tag">Tag (facultatif) :</label>
        <input type="text" id="tag" name="tag" maxlength="10"
               value="<?= htmlspecialchars($_POST['tag'] ?? '') ?>">
        <br>
        <button type="submit">Ajouter</button>
    </form>

    <h1>Liste des équipes</h1>

    <?php if (empty($equipes)): ?>
        <p>Aucune équipe trouvée.</p>
    <?php else: ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Nom</th>
                <th>Tag</th>
            </tr>
            <?php foreach ($equipes as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nom_equipe']) ?></td>
                    <td><?= htmlspecialchars($e['tag'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>