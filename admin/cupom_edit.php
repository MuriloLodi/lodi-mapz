<?php
include "../conexao.php";
$current_page = "cupons.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM tb_cupons WHERE id = ?");
$stmt->execute([$id]);
$cupom = $stmt->fetch();

if (!$cupom) {
    die("Cupom não encontrado!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = $_POST["codigo"];
    $desconto = $_POST["desconto"];
    $valido_ate = $_POST["valido_ate"];

    $stmt = $pdo->prepare("UPDATE tb_cupons SET codigo=?, desconto=?, valido_ate=? WHERE id=?");
    $stmt->execute([$codigo, $desconto, $valido_ate, $id]);

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
        <h3>Editar Cupom</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Código</label>
                <input type="text" name="codigo" class="form-control" value="<?= htmlspecialchars($cupom['codigo']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Desconto (%)</label>
                <input type="number" step="0.01" name="desconto" class="form-control" value="<?= $cupom['desconto'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Validade</label>
                <input type="date" name="valido_ate" class="form-control" value="<?= $cupom['valido_ate'] ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="cupons.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
</body>
</html>
