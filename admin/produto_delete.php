<?php
include "../conexao.php";
$current_page = "produtos.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM tb_produtos WHERE id=?");
    $stmt->execute([$id]);
}

header("Location: produtos.php");
exit;
?>
