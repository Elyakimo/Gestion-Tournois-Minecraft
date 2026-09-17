<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_NAME'];

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$database", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET client_encoding TO 'UTF8'");
    echo "Connexion réussie à la base de données PostgreSQL.";
}
catch (PDOException $e) {
    error_log("Erreur de connexion à la base de données PostgreSQL : " . $e->getMessage());
    echo "Erreur : " . $e->getMessage();
}
?>