<?php
include "../conexao.php";
$current_page = "cupons.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"];
$stmt = $pdo->prepare("DELETE FROM tb_cupons WHERE id = ?");
$stmt->execute([$id]);

header("Location: cupons.php");
exit;
