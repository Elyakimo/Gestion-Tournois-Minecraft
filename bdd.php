<?php

/* Fonction pour verifier ou se trouve la DB (wsl/windows) */
function detectDbHost(): string
{
    $isWsl = is_readable('/proc/version')
        && stripos((string) file_get_contents('/proc/version'), 'microsoft') !== false;

    if ($isWsl) {
        $gateway = trim((string) shell_exec("ip route show default 2>/dev/null | awk '{print \$3}' | head -n1"));
        if ($gateway !== '') {
            return $gateway;
        }
    }

    return 'localhost';
}

$DB_HOST = getenv('DB_HOST') ?: detectDbHost();
$DB_PORT = getenv('DB_PORT') ?: '5432';
$DB_NAME = getenv('DB_NAME') ?: 'gestion_tournois_mc';
$DB_USER = getenv('DB_USER') ?: 'postgres';
$DB_PASS = getenv('DB_PASS') ?: 'ME_CHANGER'; # MET TON MDP À TOI :)

try {
    $pdo = new PDO(
        "pgsql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ');
}