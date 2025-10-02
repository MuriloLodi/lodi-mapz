<?php
include "../conexao.php";
$current_page = "produtos.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$msg = '';
$uploadDir  = "../assets/img/";
$allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

function hasColumn(PDO $pdo, string $table, string $column): bool
{
    try {
        $s = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $s->execute([$column]);
        return (bool)$s->fetch(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        return false;
    }
}

function toEmbedUrl(?string $url): ?string
{
    $url = trim((string)$url);
    if ($url === '') return null;
    if (preg_match('~youtu\.be/([A-Za-z0-9_-]{11})~', $url, $m))  return "https://www.youtube.com/embed/{$m[1]}";
    if (preg_match('~v=([A-Za-z0-9_-]{11})~',        $url, $m))  return "https://www.youtube.com/embed/{$m[1]}";
    if (preg_match('~shorts/([A-Za-z0-9_-]{11})~',   $url, $m))  return "https://www.youtube.com/embed/{$m[1]}";
    if (preg_match('~/embed/([A-Za-z0-9_-]{11})~',   $url))      return $url;
    return null;
}

function safeName(string $name): string
{
    $name = preg_replace('/[^\w\.-]+/u', '_', $name);
    return preg_replace('/_+/', '_', $name);
}

if (isset($_POST['salvar'])) {
    $nome        = trim($_POST['nome'] ?? '');
    $preco       = (float)str_replace(',', '.', $_POST['preco'] ?? '0');
    $descricao   = trim($_POST['descricao'] ?? '');
    $tamanho_mb  = trim($_POST['tamanho_mb'] ?? '');
    $video_url   = toEmbedUrl($_POST['video_url'] ?? '') ?? '';

    $imgName = 'default.png';
    if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExt)) {
            $imgName = time() . '_' . safeName($_FILES['imagem']['name']);
            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadDir . $imgName)) {
                $msg .= "Falha ao salvar a imagem principal. ";
            }
        }
    }

    $hasVideoCol = hasColumn($pdo, 'tb_produtos', 'video_url');
    if ($hasVideoCol) {
        $stmt = $pdo->prepare("INSERT INTO tb_produtos (nome, preco, descricao, imagem, tamanho_mb, video_url) VALUES (?,?,?,?,?,?)");
        $ok = $stmt->execute([$nome, $preco, $descricao, $imgName, $tamanho_mb, $video_url]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO tb_produtos (nome, preco, descricao, imagem, tamanho_mb) VALUES (?,?,?,?,?)");
        $ok = $stmt->execute([$nome, $preco, $descricao, $imgName, $tamanho_mb]);
    }

    if ($ok) {
        $novoId = (int)$pdo->lastInsertId();

        if (!empty($_FILES['galeria']['name'][0])) {
            try {
                $cols = $pdo->query("SHOW COLUMNS FROM tb_produtos_imagens")->fetchAll(PDO::FETCH_COLUMN, 0);
                $hasImagem  = in_array('imagem', $cols, true);
                $hasCaminho = in_array('caminho', $cols, true);
                $hasOrdem   = in_array('ordem',  $cols, true);

                $insertCols = ['produto_id'];
                if ($hasImagem)  $insertCols[] = 'imagem';
                if ($hasCaminho) $insertCols[] = 'caminho';
                if ($hasOrdem)   $insertCols[] = 'ordem';

                $ph  = '(' . implode(',', array_fill(0, count($insertCols), '?')) . ')';
                $ins = $pdo->prepare("INSERT INTO tb_produtos_imagens (" . implode(',', $insertCols) . ") VALUES $ph");

                $ordem = 1;
                foreach ($_FILES['galeria']['name'] as $i => $name) {
                    if ($_FILES['galeria']['error'][$i] !== 0) continue;
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowedExt)) continue;

                    $fileName = time() . '_' . $i . '_' . safeName($name);
                    if (move_uploaded_file($_FILES['galeria']['tmp_name'][$i], $uploadDir . $fileName)) {
                        $vals = [$novoId];
                        if ($hasImagem)  $vals[] = $fileName;
                        if ($hasCaminho) $vals[] = $fileName;
                        if ($hasOrdem)   $vals[] = $ordem++;
                        $ins->execute($vals);
                    }
                }
            } catch (Throwable $e) {
                $msg .= ($msg ? ' ' : '') . "Obs: falha ao salvar galeria.";
            }
        }

        header("Location: produtos.php?ok=1");
        exit;
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
    <div class="d-flex layout">
        <?php include 'includes/sidebar.php'; ?>
        <div class="content p-4 w-100">
            <h2>Adicionar Produto</h2>
            <?php if ($msg): ?>
                <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Preço</label>
                    <input type="text" name="preco" class="form-control" placeholder="Ex: 99,90" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tamanho (MB)</label>
                    <input type="text" name="tamanho_mb" class="form-control" placeholder="Ex: 120" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="6"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Imagem principal</label>
                    <input type="file" name="imagem" class="form-control" accept="image/*">
                </div>

                <div class="col-md-6">
                    <label class="form-label">URL de Vídeo (YouTube)</label>
                    <input type="text" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=XXXXX">
                    <small class="text-muted d-block mt-1">Aceita watch/shorts/youtu.be — será convertido para <code>/embed/</code>.</small>
                </div>

                <div class="col-12">
                    <label class="form-label">Galeria (múltiplas imagens / opcional)</label>

                    <div id="inputs-galeria" class="d-flex flex-column gap-2">
                        <input type="file" name="galeria[]" class="form-control" accept="image/*">
                    </div>
                    <button type="button" id="btn-add-galeria" class="btn btn-outline-primary btn-sm mt-2">+ Adicionar mais imagens</button>
                    <div id="preview-galeria" class="d-flex flex-wrap gap-2 mt-2"></div>
                    <small class="text-muted d-block mt-1">Você pode selecionar várias de uma vez (Ctrl/Cmd) ou ir adicionando um campo por vez.</small>
                </div>

                <div class="col-12">
                    <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
                    <a href="produtos.php" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>

</body>

</html>