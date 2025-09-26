<?php
include "../conexao.php";
$current_page = basename($_SERVER['PHP_SELF']);
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['salvar'])) {
    $ids = $_POST['produto'];
    foreach ($ids as $posicao => $produto_id) {
        $stmtCheck = $pdo->prepare("SELECT * FROM tb_vendidos WHERE posicao = ?");
        $stmtCheck->execute([$posicao + 1]);
        if ($stmtCheck->rowCount() > 0) {
            $stmtUpdate = $pdo->prepare("UPDATE tb_vendidos SET produto_id = ? WHERE posicao = ?");
            $stmtUpdate->execute([$produto_id, $posicao + 1]);
        } else {
            $stmtInsert = $pdo->prepare("INSERT INTO tb_vendidos (produto_id, posicao) VALUES (?, ?)");
            $stmtInsert->execute([$produto_id, $posicao + 1]);
        }
    }
    $msg = "Produtos mais vendidos atualizados com sucesso!";
}

$stmt = $pdo->query("SELECT * FROM tb_produtos ORDER BY nome ASC");
$produtos = $stmt->fetchAll();

$vendidos = [];
$stmtVendidos = $pdo->query("SELECT * FROM tb_vendidos ORDER BY posicao ASC");
while ($row = $stmtVendidos->fetch()) {
    $vendidos[$row['posicao']] = $row['produto_id'];
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
            <h2>Configurar Produtos Mais Vendidos</h2>
            <?php if (isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

            <form method="POST">
                <div class="row">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="col-md-3 mb-3">
                            <label>Posição <?php echo $i; ?></label>
                            <select name="produto[]" class="form-select" required>
                                <option value="">-- Selecionar Produto --</option>
                                <?php foreach ($produtos as $p): ?>
                                    <option value="<?php echo $p['id']; ?>" <?php if (isset($vendidos[$i]) && $vendidos[$i] == $p['id']) echo "selected"; ?>>
                                        <?php echo $p['nome']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endfor; ?>
                </div>
                <button type="submit" name="salvar" class="btn btn-primary">Salvar</button>
            </form>

            <hr>
            <a href="produto_add.php" class="btn btn-success mb-3">Adicionar Produto</a>

            <h3>Todos os Produtos</h3>
            <table class="table table-striped mt-2">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo $p['nome']; ?></td>
                            <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo substr($p['descricao'], 0, 50) . '...'; ?></td>
                            <td>
                                <a href="produto_edit.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="produto_delete.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>