<?php
include "../conexao.php";
$current_page = "produtos.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: produtos.php");
    exit;
}

$id = $_GET['id'];
$msg = '';

$stmt = $pdo->prepare("SELECT * FROM tb_produtos WHERE id=?");
$stmt->execute([$id]);
$produto = $stmt->fetch();

if (!$produto) {
    header("Location: produtos.php");
    exit;
}

if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imgName = time().'_'.$_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../assets/img/$imgName");
        $stmtUpdate = $pdo->prepare("UPDATE tb_produtos SET nome=?, preco=?, descricao=?, imagem=? WHERE id=?");
        $stmtUpdate->execute([$nome, $preco, $descricao, $imgName, $id]);
    } else {
        $stmtUpdate = $pdo->prepare("UPDATE tb_produtos SET nome=?, preco=?, descricao=? WHERE id=?");
        $stmtUpdate->execute([$nome, $preco, $descricao, $id]);
    }

    $msg = "Produto atualizado com sucesso!";
    $stmt = $pdo->prepare("SELECT * FROM tb_produtos WHERE id=?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch();
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
        <h2>Editar Produto</h2>
        <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Preço</label>
                <input type="number" step="0.01" name="preco" class="form-control" value="<?= $produto['preco'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Descrição</label>
                <textarea name="descricao" class="form-control"><?= htmlspecialchars($produto['descricao']) ?></textarea>
            </div>
            <div class="mb-3">
                <label>Imagem (deixe em branco para manter)</label>
                <input type="file" name="imagem" class="form-control">
            </div>
            <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
            <a href="produtos.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
