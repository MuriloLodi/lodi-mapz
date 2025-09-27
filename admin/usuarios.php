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

$stmtDiscord = $pdo->query("SELECT * FROM users_discord ORDER BY id DESC");
$discordUsers = $stmtDiscord->fetchAll();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmtDel = $pdo->prepare("DELETE FROM usuario WHERE id = ?");
    $stmtDel->execute([$id]);
    header("Location: usuarios.php");
    exit;
}
if (isset($_GET['delete_discord'])) {
    $id = $_GET['delete_discord'];
    $stmtDel = $pdo->prepare("DELETE FROM users_discord WHERE id = ?");
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

        <h4>Usuários do Sistema</h4>
        <table class="table table-striped mb-5">
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

        <h4>Usuários do Discord</h4>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Discord ID</th>
                    <th>Username</th>
                    <th>Global Name</th>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Criado em</th>
                    <th>Atualizado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($discordUsers as $d): ?>
                <tr>
                    <td><?= $d['id'] ?></td>
                    <td><?= htmlspecialchars($d['discord_id']) ?></td>
                    <td><?= htmlspecialchars($d['username']) ?></td>
                    <td><?= htmlspecialchars($d['global_name']) ?></td>
                    <td><?= htmlspecialchars($d['email']) ?></td>
                    <td>
                        <?php if (!empty($d['avatar'])): ?>
                            <img src="https://cdn.discordapp.com/avatars/<?= $d['discord_id'] ?>/<?= $d['avatar'] ?>.png" 
                                 alt="Avatar" width="40" height="40" class="rounded-circle">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?= $d['created_at'] ?></td>
                    <td><?= $d['updated_at'] ?></td>
                    <td>
                        <a href="usuarios.php?delete_discord=<?= $d['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Deseja realmente excluir este usuário do Discord?')">Excluir</a>
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
