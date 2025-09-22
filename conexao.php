<?php
$HOST = '192.168.0.50'; // IP do PC que será o servidor do banco
$DB   = 'loja';
$USER = 'murilo';
$PASS = 'Lucy135=';

$dsn = "mysql:host={$HOST};dbname={$DB};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $USER, $PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Erro ao conectar: ' . $e->getMessage());
}
