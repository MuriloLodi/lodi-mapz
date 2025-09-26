<?php
include "../conexao.php";
$current_page = basename($_SERVER['PHP_SELF']);
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM usuario ORDER BY id DESC");
$usuarios = $stmt->fetchAll();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmtDel = $pdo->prepare("DELETE FROM usuario WHERE id = ?");
    $stmtDel->execute([$id]);
    header("Location: usuarios.php");
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

    <div class="content p-4 w-100">
        <h2>Gerenciar Usuários</h2>
        <a href="usuario_add.php" class="btn btn-success mb-3">Adicionar Usuário</a>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['tipo']) ?></td>
                    <td><?= $u['criado_em'] ?></td>
                    <td>
                        <a href="usuario_edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="usuarios.php?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Deseja realmente excluir este usuário?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
