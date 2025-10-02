
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'conexao.php';

function columnExists(PDO $pdo, string $table, string $column): bool {
    try {
        $s = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $s->execute([$column]);
        return (bool)$s->fetch(PDO::FETCH_ASSOC);
    } catch (Throwable $e) { return false; }
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); die('Produto inválido.'); }

$hasVideo = columnExists($pdo, 'tb_produtos', 'video_url');
$sql = "SELECT id, nome, descricao, preco, imagem, tamanho_mb, destaque"
     . ($hasVideo ? ", COALESCE(video_url,'') AS video_url" : ", '' AS video_url")
     . " FROM tb_produtos WHERE id=? LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produto) { http_response_code(404); die('Produto não encontrado.'); }

$imagens = [];
try {
    $q = $pdo->prepare("
        SELECT COALESCE(NULLIF(caminho,''), NULLIF(imagem,'')) AS arquivo
        FROM tb_produtos_imagens
        WHERE produto_id = ?
        ORDER BY ordem ASC, id ASC
    ");
    $q->execute([$id]);
    $imagens = array_values(array_filter($q->fetchAll(PDO::FETCH_COLUMN) ?: [], fn($v) => is_string($v) && trim($v) !== ''));
} catch (Throwable $e) { /* ignora */ }

$bg = 'default.png';
if (!empty($produto['imagem'])) {
    $bg = $produto['imagem'];
} elseif (!empty($imagens[0])) {
    $bg = $imagens[0];
}

if (empty($imagens)) {
    $imagens = [ !empty($produto['imagem']) ? $produto['imagem'] : 'default.png' ];
}

$destaques = [];
try {
    $qd = $pdo->query("SELECT id, nome, preco FROM tb_produtos WHERE destaque=1 AND id<>".(int)$id." ORDER BY id DESC LIMIT 3");
    $destaques = $qd->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include 'includes/head.php'; ?>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($produto['nome']) ?> • Loja</title>
    <style>
        .produto-hero{
            background: linear-gradient(to right, rgba(15,26,44,.85) 20%, rgba(15,26,44,0) 50%, rgba(15,26,44,.85) 80%),
                        url('assets/img/<?= htmlspecialchars($bg) ?>');
            background-size: cover; background-position: center 60%;
            color:#fff; padding:30px; border-radius:10px;
        }
        .price{font-size:22px;font-weight:700;color:#ffc107;}
        .btn-add{display:inline-block;background:#ffc107;color:#000;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600;}
        .btn-back{color:#d0d6e3;text-decoration:none;display:inline-block;margin-top:8px;}
        .produto-card{background:#0f1a2c;color:#fff;padding:14px;border-radius:12px;}
        .galeria{display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:.75rem;}
        .galeria img{width:100%;height:110px;object-fit:cover;border-radius:8px;}
        .badge-novo{position:relative;top:-2px;margin-left:.5rem;}
        .lista-destaques li{padding:.35rem 0;border-bottom:1px solid rgba(255,255,255,.08);}
        .lista-destaques li:last-child{border-bottom:0;}
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container my-4">
    <div class="row produto-hero align-items-center">
        <div class="col">
            <h1 class="mb-1">
                <?= htmlspecialchars($produto['nome']) ?>
                <?php if ((int)$produto['destaque'] === 1): ?>
                    <span class="badge bg-warning text-dark badge-novo">Novo</span>
                <?php endif; ?>
            </h1>
            <p class="price mb-0">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
        </div>
        <div class="col-auto text-end">
            <a href="carrinho.php?add=<?= (int)$produto['id'] ?>" class="btn-add">➕ ADICIONAR AO CARRINHO</a><br>
            <a href="loja.php" class="btn-back">⬅ Voltar aos Produtos</a>
        </div>
    </div>

    <div class="row g-4 mt-3">
        <div class="col-lg-8">
            <div class="produto-card mb-3">
                <h5 class="mb-2">Descrição</h5>
                <p class="mb-0"><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>
            </div>

            <div class="produto-card mb-3">
                <h5 class="mb-2">Especificações</h5>
                <ul class="mb-0">
                    <?php if (!empty($produto['tamanho_mb'])): ?>
                        <li><strong>Peso:</strong> <?= htmlspecialchars($produto['tamanho_mb']) ?> MB</li>
                    <?php endif; ?>
                    <li><strong>Compatibilidade:</strong> Funciona somente no MTA:SA.</li>
                    <li><strong>Licença:</strong> Uso exclusivo no seu servidor conforme termos.</li>
                </ul>
            </div>

            <?php if (!empty($produto['video_url'])): ?>
            <div class="produto-card">
                <h5 class="mb-2">Vídeo</h5>
                <div class="ratio ratio-16x9">
                    <iframe src="<?= htmlspecialchars($produto['video_url']) ?>" allowfullscreen></iframe>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="produto-card mb-3">
                <h5 class="mb-2">Galeria</h5>
                <div class="galeria">
                    <?php foreach ($imagens as $img): ?>
                        <img src="assets/img/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!empty($destaques)): ?>
            <div class="produto-card">
                <h5 class="mb-2">Produtos em Destaque</h5>
                <ul class="mb-0 lista-destaques">
                    <?php foreach ($destaques as $d): ?>
                        <li class="d-flex justify-content-between align-items-center">
                            <a class="text-white text-decoration-none text-truncate" href="produto.php?id=<?= (int)$d['id'] ?>" title="<?= htmlspecialchars($d['nome']) ?>">
                                <?= htmlspecialchars($d['nome']) ?>
                            </a>
                            <strong class="ms-2">R$ <?= number_format($d['preco'], 2, ',', '.') ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($_SESSION['toasts']) && is_array($_SESSION['toasts'])): ?>
        <div class="toast-container position-fixed bottom-0 start-0 p-3 d-flex flex-column" style="z-index:9999; gap:.5rem;">
            <?php foreach ($_SESSION['toasts'] as $toast): ?>
                <div class="toast align-items-center text-white <?= $toast['tipo'] == 'sucesso' ? 'bg-success' : 'bg-danger' ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body"><?= htmlspecialchars($toast['mensagem']) ?></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            <?php endforeach; unset($_SESSION['toasts']); ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
</body>
</html>