<?php
include "../conexao.php";
$current_page = "cupons.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = $_POST["codigo"];
    $desconto = $_POST["desconto"];
    $valido_ate = $_POST["valido_ate"];

    $stmt = $pdo->prepare("INSERT INTO tb_cupons (codigo, desconto, valido_ate) VALUES (?, ?, ?)");
    $stmt->execute([$codigo, $desconto, $valido_ate]);

    header("Location: cupons.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <?php include 'includes/head.php'; ?>
</head>
<body>
<div class="d-flex vh-100">
    <?php include 'includes/sidebar.php'; ?>

    <div class="d-flex flex-column w-100 p-4">
        <h3>Adicionar Cupom</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Código</label>
                <input type="text" name="codigo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Desconto (%)</label>
                <input type="number" step="0.01" name="desconto" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Validade</label>
                <input type="date" name="valido_ate" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="cupons.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
</body>
</html>
