<?php
include "../conexao.php";
$current_page = basename($_SERVER['PHP_SELF']);
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM tb_cupons ORDER BY id DESC");
$cupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Gerenciar Cupons</h3>
            <a href="cupom_add.php" class="btn btn-primary">+ Adicionar Cupom</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Desconto (%)</th>
                    <th>Validade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cupons as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['codigo']) ?></td>
                    <td><?= number_format($c['desconto'], 2, ',', '.') ?>%</td>
                    <td><?= date("d/m/Y", strtotime($c['valido_ate'])) ?></td>
                    <td>
                        <a href="cupom_edit.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="cupom_delete.php?id=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir este cupom?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
