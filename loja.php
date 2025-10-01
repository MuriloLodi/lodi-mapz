<?php
include 'conexao.php';
include 'carrinho.php';
$carrinhoProdutos = getCarrinhoCompleto($pdo);
$totalCarrinho = getTotalCarrinho($pdo);
$descontoAplicado = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cupom'])) {
    $cupom = trim($_POST['cupom']);
    $stmt = $pdo->prepare("SELECT * FROM tb_cupons WHERE codigo = ? AND valido_ate >= CURDATE()");
    $stmt->execute([$cupom]);
    $cupomData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cupomData) {
        $descontoAplicado = $cupomData['desconto'];
        $_SESSION['cupom_desconto'] = $descontoAplicado;
    } else {
        $_SESSION['cupom_desconto'] = 0;
    }
}

if (isset($_SESSION['cupom_desconto'])) {
    $descontoAplicado = $_SESSION['cupom_desconto'];
}

$totalComDesconto = $totalCarrinho - ($totalCarrinho * ($descontoAplicado / 100));
if (session_status() === PHP_SESSION_NONE) session_start();

function getProdutos(PDO $pdo, $search = '', $ordenar = 'recentes', $precoMax = null): array
{
    $orderBy = "id DESC";
    switch ($ordenar) {
        case 'preco_crescente':
            $orderBy = "preco ASC";
            break;
        case 'preco_decrescente':
            $orderBy = "preco DESC";
            break;
        case 'recentes':
        default:
            $orderBy = "id DESC";
    }

    $sql = "SELECT id, nome, imagem, preco, destaque, descricao, tamanho_mb FROM tb_produtos WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND nome LIKE :search";
        $params[':search'] = "%$search%";
    }

    if (!empty($precoMax)) {
        $sql .= " AND preco <= :precoMax";
        $params[':precoMax'] = $precoMax;
    }

    $sql .= " ORDER BY $orderBy";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$search = $_GET['search'] ?? '';
$ordenar = $_GET['ordenar'] ?? 'recentes';
$precoMax = $_GET['preco'] ?? null;

$produtos = getProdutos($pdo, $search, $ordenar, $precoMax);
?>

<!doctype html>
<html lang="pt-br">

<head>
    <?php include 'includes/head.php' ?>
</head>

<body>
    <?php include 'includes/header.php' ?>

    <section class="loja container mt-5 mb-5">
        <form method="GET" id="formFiltros" class="filtros headerloja d-flex justify-content-between mb-4 align-items-end">

            <div class="w-25">
                <label for="searchProduto" class="form-label text-white">
                    <i class="bi bi-search"></i> Pesquisar Produto
                </label>
                <input type="text" name="search" id="searchProduto"
                    class="form-control"
                    placeholder="Digite o nome"
                    value="<?= htmlspecialchars($search) ?>">
            </div>

            <div class="w-25">
                <label for="ordenarProdutos" class="form-label text-white">
                    <i class="bi bi-filter"></i> Ordenar
                </label>
                <select class="form-select" name="ordenar" id="ordenarProdutos">
                    <option value="recentes" <?= $ordenar == 'recentes' ? 'selected' : '' ?>>Mais Recentes</option>
                    <option value="preco_crescente" <?= $ordenar == 'preco_crescente' ? 'selected' : '' ?>>Preço Crescente</option>
                    <option value="preco_decrescente" <?= $ordenar == 'preco_decrescente' ? 'selected' : '' ?>>Preço Decrescente</option>
                </select>
            </div>

            <div class="w-25">
                <label for="rangePreco" class="form-label text-white">
                    <i class="bi bi-cash-coin"></i> Preço Máximo: R$ <span id="valorAtual"><?= $precoMax ?: '100' ?></span>
                </label>
                <input type="range" min="0" max="100" step="1"
                    name="preco" id="rangePreco"
                    class="form-range"
                    value="<?= htmlspecialchars($precoMax ?? 100) ?>">
            </div>
        </form>

        <div class="row">

            <div class="col-lg-9 col-md-8">
                <div class="row g-4">
                    <?php if (!empty($produtos)): ?>
                        <?php foreach ($produtos as $p): ?>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="card h-100 shadow-sm border-0 rounded-4">
                                    <?php if ($p['destaque']): ?>
                                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">Novo</span>
                                    <?php endif; ?>

                                    <img src="assets/img/<?= htmlspecialchars($p['imagem']) ?>"
                                        alt="<?= htmlspecialchars($p['nome']) ?>"
                                        class="card-img-top">

                                    <div class="card-body text-center">
                                        <h6 class="card-title text-white text-truncate" title="<?= htmlspecialchars($p['nome']) ?>">
                                            <?= htmlspecialchars($p['nome']) ?>
                                        </h6>
                                        <p class="card-text fw-bold text-preco mb-2">
                                            R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                                        </p>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="carrinho.php?add=<?= $p['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-cart-plus-fill"></i>
                                            </a>
                                            <button
                                                class="btn btn-secondary btn-sm btn-info-produto"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalInfo"
                                                data-nome="<?= htmlspecialchars($p['nome']) ?>"
                                                data-descricao="<?= htmlspecialchars($p['descricao']) ?>"
                                                data-preco="<?= number_format($p['preco'], 2, ',', '.') ?>"
                                                data-tamanho="<?= htmlspecialchars($p['tamanho_mb']) ?>">
                                                <i class="bi bi-info-circle-fill"></i> Informações
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-white">Nenhum produto encontrado com os filtros selecionados.</p>
                    <?php endif; ?>
                </div>
            </div>


            <div class="col-lg-3 col-md-4">
                <div class="card1 p-3 card-carrinho">
                    <h5 class="text-white mb-3">
                        <i class="bi bi-cart-fill me-2 text-white"></i> Carrinho
                    </h5>

                    <?php if (!empty($carrinhoProdutos)): ?>
                        <?php foreach ($carrinhoProdutos as $item): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 carrinho-item-modern">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="icon-box me-2">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="flex-grow-1 text-truncate">
                                        <div class="fw-semibold text-white small text-truncate" title="<?= htmlspecialchars($item['nome']) ?>">
                                            <?= htmlspecialchars($item['nome']) ?>
                                        </div>
                                        <div class="text-warning fw-bold small">
                                            <?= $item['quantidade'] ?>x R$ <?= number_format($item['preco'], 2, ',', '.') ?>
                                        </div>
                                    </div>
                                </div>
                                <a href="carrinho.php?remove=<?= $item['id'] ?>" class="btn-remove-item ms-2" title="Remover">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-white small">Seu carrinho está vazio</p>
                    <?php endif; ?>

                    <hr class="text-secondary my-3">


                    <form method="POST">
                        <div class="mb-2">
                            <label class="form-label small text-white">Cupom de Desconto</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="cupom" class="form-control" placeholder="Insira o cupom">
                                <button class="btn btn-secondary">Aplicar</button>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label small text-white">Método de Pagamento</label>
                            <select name="metodo_pagamento" class="form-select form-select-sm">
                                <option value="">Selecione o Método</option>
                                <option value="pix">PIX</option>
                                <option value="cartao">Cartão</option>
                                <option value="boleto">Boleto</option>
                            </select>
                        </div>
                    </form>


                    <div class="totais mb-3">
                        <div class="d-flex justify-content-between small text-white mb-1">
                            <span>Sub-Total</span>
                            <span>R$ <?= number_format($totalCarrinho, 2, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between small text-white mb-1">
                            <span>Desconto</span>
                            <span><?= number_format($descontoAplicado, 2, ',', '.') ?>%</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold text-white">
                            <span>Total</span>
                            <span>R$ <?= number_format($totalComDesconto, 2, ',', '.') ?></span>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="termosCheck">
                        <label class="form-check-label small text-white" for="termosCheck">
                            Eu concordo com os <span class="text-warning termos-link" style="cursor:pointer;">Termos de Serviço</span> e desejo continuar minha compra
                        </label>
                    </div>

                    <form action="checkout.php" method="POST">
                        <button type="submit" class="btn btn-warning w-100 fw-bold" id="confirmarPedido" disabled>
                            Confirmar Pedido
                        </button>
                    </form>



                </div>

            </div>
        </div>

    </section>


    <div class="toast-container position-fixed bottom-0 start-0 p-3 d-flex flex-column" style="z-index:9999; gap: .5rem;">
        <?php if (isset($_SESSION['toasts']) && is_array($_SESSION['toasts'])): ?>
            <?php foreach ($_SESSION['toasts'] as $toast): ?>
                <div class="toast align-items-center text-white 
                <?= $toast['tipo'] == 'sucesso' ? 'bg-success' : 'bg-danger' ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= htmlspecialchars($toast['mensagem']) ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['toasts']); ?>
        <?php endif; ?>
    </div>
                  
    <?php include 'includes/modal/modaltermos.php' ?>
    <?php include 'includes/modal/modalinfo.php' ?>
    <?php include 'includes/footer.php' ?>
    <?php include 'includes/scripts.php' ?>

    <script>
        const form = document.getElementById("formFiltros");
        const search = document.getElementById("searchProduto");
        const ordenar = document.getElementById("ordenarProdutos");
        const range = document.getElementById("rangePreco");
        const span = document.getElementById("valorAtual");

        range.addEventListener("input", function() {
            span.textContent = this.value;
        });

        ordenar.addEventListener("change", () => form.submit());
        range.addEventListener("change", () => form.submit());
        search.addEventListener("keyup", () => {
            clearTimeout(search.timer);
            search.timer = setTimeout(() => form.submit(), 600);
        });
    </script>
</body>

</html>