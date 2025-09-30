<?php
include 'conexao.php';
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

    $sql = "SELECT id, nome, imagem, preco, destaque FROM tb_produtos WHERE 1=1";
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

    <section class="loja container mt-5">
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
            <div class="col-md-9 row">
                <?php if (!empty($produtos)): ?>
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
                                    <p class="card-text fw-bold text-preco">
                                        R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                                    </p>

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
                <?php else: ?>
                    <p class="text-center text-white">Nenhum produto encontrado com os filtros selecionados.</p>
                <?php endif; ?>
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