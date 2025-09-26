<?php
include "../conexao.php";
$current_page = "usuarios.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: usuarios.php");
    exit;
}

$id = $_GET['id'];
$msg = '';

$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header("Location: usuarios.php");
    exit;
}

if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];
    
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $stmtUpdate = $pdo->prepare("UPDATE usuario SET nome=?, email=?, senha=?, tipo=? WHERE id=?");
        $stmtUpdate->execute([$nome, $email, $senha, $tipo, $id]);
    } else {
        $stmtUpdate = $pdo->prepare("UPDATE usuario SET nome=?, email=?, tipo=? WHERE id=?");
        $stmtUpdate->execute([$nome, $email, $tipo, $id]);
    }

    $msg = "Usuário atualizado com sucesso!";

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();
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
        <h2>Editar Usuário</h2>
        <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Senha (deixe em branco para manter)</label>
                <input type="password" name="senha" class="form-control">
            </div>
            <div class="mb-3">
                <label>Tipo</label>
                <select name="tipo" class="form-select" required>
                    <option value="usuario" <?= $usuario['tipo']=='usuario'?'selected':'' ?>>Usuário</option>
                    <option value="admin" <?= $usuario['tipo']=='admin'?'selected':'' ?>>Admin</option>
                </select>
            </div>
            <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
            <a href="usuarios.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
