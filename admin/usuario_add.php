<?php
include "../conexao.php";
$current_page = "usuarios.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$msg = '';

if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // hash da senha
    $tipo = $_POST['tipo'];

    $stmt = $pdo->prepare("INSERT INTO usuario (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$nome, $email, $senha, $tipo])) {
        $msg = "Usuário adicionado com sucesso!";
    } else {
        $msg = "Erro ao adicionar usuário!";
    }
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
        <h2>Adicionar Usuário</h2>
        <?php if($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Tipo</label>
                <select name="tipo" class="form-select" required>
                    <option value="usuario">Usuário</option>
                    <option value="admin">Admin</option>
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
