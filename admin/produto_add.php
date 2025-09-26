<?php
include "../conexao.php";
$current_page = "produtos.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$msg = '';

if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imgName = time().'_'.$_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../assets/img/$imgName");
    } else {
        $imgName = null;
    }

    $stmt = $pdo->prepare("INSERT INTO tb_produtos (nome, preco, descricao, imagem) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$nome, $preco, $descricao, $imgName])) {
        $msg = "Produto adicionado com sucesso!";
    } else {
        $msg = "Erro ao adicionar produto!";
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
        <h2>Adicionar Produto</h2>
        <?php if($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Preço</label>
                <input type="number" step="0.01" name="preco" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Descrição</label>
                <textarea name="descricao" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Imagem</label>
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
