<?php
include 'conexao.php';
if (session_status() === PHP_SESSION_NONE) session_start();

function getProdutos(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT id, nome, imagem, preco, destaque FROM tb_produtos ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$produtos = getProdutos($pdo);
?>

<!doctype html>
<html lang="pt-br">

<head>
    <?php include 'includes/head.php' ?>
</head>

<body>
    <?php include 'includes/header.php' ?>

    <section class="loja container mt-5">
        <div class="filtros d-flex justify-content-between mb-4">
            <input type="text" placeholder="Pesquise um Produto" class="form-control w-25" id="searchProduto">
            <select class="form-select w-25" id="ordenarProdutos">
                <option value="recentes">Mais Recentes</option>
                <option value="preco_crescente">Preço Crescente</option>
                <option value="preco_decrescente">Preço Decrescente</option>
            </select>
            <div class="filtro-valor w-25">
                <label>Filtro de Valores</label>
                <input type="range" min="0" max="100" id="rangePreco" class="form-range">
            </div>
        </div>

        <div class="row">
            <div class="col-md-9 row">
                <?php foreach ($produtos as $p): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 rounded-4 position-relative">
                            <?php if ($p['destaque']): ?>
                                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">Novo Lançamento</span>
                            <?php endif; ?>

                            <div class="position-relative">
                                <img src="assets/img/<?= htmlspecialchars($p['imagem']) ?>"
                                    alt="<?= htmlspecialchars($p['nome']) ?>"
                                    class="card-img">
                            </div>

                            <div class="card-body text-center">
                                <h5 class="card-title text-white"><?= htmlspecialchars($p['nome']) ?></h5>
                                <p class="card-text fw-bold text-preco">R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>

                                <div class="d-flex justify-content-center mt-3">
                                    <button class="btn btn-warning btn-sm me-2" title="Adicionar ao Carrinho">
                                        <i class="bi bi-cart-plus-fill"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-sm" title="Ver Detalhes">
                                        <i class="bi bi-info-circle-fill"></i> Informações
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>

            <div class="col-md-3">
                <div class="card p-3">
                    <h5>Carrinho</h5>
                    <p>Seu Carrinho está Vazio</p>
                    <input type="text" placeholder="Cupom de Desconto" class="form-control mb-2">
                    <select class="form-select mb-2">
                        <option>Selecione o Método</option>
                    </select>
                    <div class="d-flex justify-content-between">
                        <span>Sub-Total:</span><span>R$ 0,00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Desconto:</span><span>0%</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span><span>R$ 0,00</span>
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="termosCheck">
                        <label class="form-check-label" for="termosCheck">
                            Concordo com os Termos de Serviço
                        </label>
                    </div>
                    <button class="btn btn-warning w-100 mt-3">Confirmar Pedido</button>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php' ?>
    <?php include 'includes/scripts.php' ?>
</body>

</html>