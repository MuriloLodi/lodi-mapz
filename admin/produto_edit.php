<?php
include "../conexao.php";
$current_page = "produtos.php";
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header("Location: produtos.php");
    exit;
}
$id = (int)$_GET['id'];

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
    // 
    if (preg_match('~youtu\.be/([A-Za-z0-9_-]{11})~', $url, $m))  return "https://www.youtube.com/embed/{$m[1]}";
    // 
    if (preg_match('~v=([A-Za-z0-9_-]{11})~',        $url, $m))  return "https://www.youtube.com/embed/{$m[1]}";
    // 
    if (preg_match('~shorts/([A-Za-z0-9_-]{11})~',   $url, $m))  return "https://www.youtube.com/embed/{$m[1]}";
    // 
    if (preg_match('~/embed/([A-Za-z0-9_-]{11})~',   $url))      return $url;
    return null;
}

if (isset($_GET['del_img']) && filter_var($_GET['del_img'], FILTER_VALIDATE_INT)) {
    $delId = (int)$_GET['del_img'];
    try {
        $q = $pdo->prepare("SELECT imagem, caminho FROM tb_produtos_imagens WHERE id=? AND produto_id=?");
        $q->execute([$delId, $id]);
        if ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $arquivo = !empty($row['caminho']) ? $row['caminho'] : $row['imagem'];
            if ($arquivo && file_exists($uploadDir . $arquivo)) @unlink($uploadDir . $arquivo);
            $pdo->prepare("DELETE FROM tb_produtos_imagens WHERE id=? AND produto_id=?")->execute([$delId, $id]);
            $msg = "Imagem removida da galeria.";
        }
    } catch (Throwable $e) {
        $msg = "Erro ao remover imagem.";
    }
}

$stmt = $pdo->prepare("SELECT * FROM tb_produtos WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produto) {
    header("Location: produtos.php");
    exit;
}

if (isset($_POST['salvar'])) {
    $nome        = trim($_POST['nome'] ?? '');
    $preco       = (float)str_replace(',', '.', $_POST['preco'] ?? 0);
    $descricao   = trim($_POST['descricao'] ?? '');
    $tamanho_mb  = trim($_POST['tamanho_mb'] ?? '');
    $video_url   = toEmbedUrl($_POST['video_url'] ?? '') ?? '';

    $imgName = $produto['imagem'];
    if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExt)) {
            $newName = time() . '_' . preg_replace('/\s+/', '_', $_FILES['imagem']['name']);
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadDir . $newName)) {
                if (!empty($imgName) && file_exists($uploadDir . $imgName)) @unlink($uploadDir . $imgName);
                $imgName = $newName;
            }
        }
    }

    $hasVideoCol = hasColumn($pdo, 'tb_produtos', 'video_url');
    if ($hasVideoCol) {
        $up = $pdo->prepare("UPDATE tb_produtos SET nome=?, preco=?, descricao=?, imagem=?, tamanho_mb=?, video_url=? WHERE id=?");
        $ok = $up->execute([$nome, $preco, $descricao, $imgName, $tamanho_mb, $video_url, $id]);
    } else {
        $up = $pdo->prepare("UPDATE tb_produtos SET nome=?, preco=?, descricao=?, imagem=?, tamanho_mb=? WHERE id=?");
        $ok = $up->execute([$nome, $preco, $descricao, $imgName, $tamanho_mb, $id]);
    }

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

            foreach ($_FILES['galeria']['name'] as $i => $name) {
                if ($_FILES['galeria']['error'][$i] === 0) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowedExt)) continue;

                    $fileName = time() . '_' . $i . '_' . preg_replace('/\s+/', '_', $name);
                    if (move_uploaded_file($_FILES['galeria']['tmp_name'][$i], $uploadDir . $fileName)) {
                        $vals = [$id];
                        if ($hasImagem)  $vals[] = $fileName;
                        if ($hasCaminho) $vals[] = $fileName;
                        if ($hasOrdem)   $vals[] = 0;
                        $ins->execute($vals);
                    }
                }
            }
        } catch (Throwable $e) {
            $msg .= ($msg ? ' ' : '') . "Obs: falha ao salvar galeria.";
        }
    }

    $msg = $ok ? "Produto atualizado com sucesso!" : "Erro ao atualizar produto.";

    $stmt = $pdo->prepare("SELECT * FROM tb_produtos WHERE id=? LIMIT 1");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
}

$galeria = [];
try {
    $qg = $pdo->prepare("
        SELECT id,
               COALESCE(NULLIF(caminho,''), NULLIF(imagem,'')) AS arquivo
        FROM tb_produtos_imagens
        WHERE produto_id=?
        ORDER BY ordem ASC, id ASC
    ");
    $qg->execute([$id]);
    $galeria = array_values(array_filter($qg->fetchAll(PDO::FETCH_ASSOC) ?: [], fn($r) => !empty($r['arquivo'])));
} catch (Throwable $e) {
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
            <h2>Editar Produto</h2>
            <?php if ($msg): ?>
                <div class="alert alert-<?= (strpos($msg, 'sucesso') !== false) ? 'success' : 'info' ?>"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Preço</label>
                    <input type="text" name="preco" class="form-control" value="<?= htmlspecialchars(number_format((float)$produto['preco'], 2, ',', '')) ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tamanho (MB)</label>
                    <input type="text" name="tamanho_mb" class="form-control" value="<?= htmlspecialchars($produto['tamanho_mb'] ?? '') ?>" placeholder="Ex: 120">
                </div>

                <div class="col-12">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="6"><?= htmlspecialchars($produto['descricao']) ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Imagem principal (deixe em branco para manter)</label>
                    <input type="file" name="imagem" class="form-control" accept="image/*">
                    <?php if (!empty($produto['imagem'])): ?>
                        <small class="text-muted d-block mt-1">Atual: <code><?= htmlspecialchars($produto['imagem']) ?></code></small>
                        <img src="../assets/img/<?= htmlspecialchars($produto['imagem']) ?>" alt="" class="img-thumbnail mt-2" style="max-width:160px">
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">URL de Vídeo (YouTube)</label>
                    <input type="text" name="video_url" class="form-control"
                        value="<?= htmlspecialchars($produto['video_url'] ?? '') ?>"
                        placeholder="https://www.youtube.com/watch?v=XXXXX">
                    <small class="text-muted d-block mt-1">
                        Aceita watch/shorts/youtu.be — será convertido para <code>/embed/</code> ao salvar.
                    </small>
                </div>

                <div class="col-12">
                    <label class="form-label">Galeria (múltiplas imagens / opcional)</label>

                    <div id="inputs-galeria" class="d-flex flex-column gap-2">
                        <input type="file" name="galeria[]" class="form-control" accept="image/*">
                    </div>
                    <button type="button" id="btn-add-galeria" class="btn btn-outline-primary btn-sm mt-2">+ Adicionar mais imagens</button>
                    <div id="preview-galeria" class="d-flex flex-wrap gap-2 mt-2"></div>
                    <small class="text-muted d-block mt-1">Você pode selecionar várias de uma vez (Ctrl/Cmd) ou ir adicionando um campo por vez.</small>

                    <?php if (!empty($galeria)): ?>
                        <hr>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($galeria as $g): ?>
                                <div class="border rounded p-2 text-center" style="width:160px">
                                    <img src="../assets/img/<?= htmlspecialchars($g['arquivo']) ?>" style="max-width:140px; max-height:110px; object-fit:cover" alt="">
                                    <div class="small mt-1 text-truncate" title="<?= htmlspecialchars($g['arquivo']) ?>">
                                        <?= htmlspecialchars($g['arquivo']) ?>
                                    </div>
                                    <div>
                                        <a class="btn btn-sm btn-outline-danger mt-2"
                                            href="?id=<?= $id ?>&del_img=<?= (int)$g['id'] ?>"
                                            onclick="return confirm('Excluir esta imagem da galeria?')">Excluir</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <button type="submit" name="salvar" class="btn btn-success">Salvar</button>
                    <a href="produtos.php" class="btn btn-secondary">Voltar</a>
                </div>
            </form>

            <?php
            $embed = toEmbedUrl($produto['video_url'] ?? '');
            if ($embed):
            ?>
                <div class="mt-4">
                    <h5>Prévia do vídeo</h5>
                    <div class="ratio ratio-16x9">
                        <iframe src="<?= htmlspecialchars($embed) ?>" title="YouTube"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen referrerpolicy="strict-origin-when-cross-origin" loading="lazy"></iframe>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>

</body>

</html>