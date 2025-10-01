<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'conexao.php';

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];

    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id] = (int)$_SESSION['carrinho'][$id] + 1;
    } 

    else {
        $_SESSION['carrinho'][$id] = 1;
    }

    header('Location: loja.php');
    exit;
}

if (isset($_GET['remove'])) {
    $id = (int) $_GET['remove'];
    unset($_SESSION['carrinho'][$id]);
    header('Location: loja.php');
    exit;
}


function getCarrinhoCompleto(PDO $pdo): array {
    $carrinho = $_SESSION['carrinho'] ?? [];
    $produtos = [];

    if (empty($carrinho)) {
        return [];
    }


    $ids = array_map('intval', array_keys($carrinho));

    if (empty($ids)) {
        return [];
    }

    $inQuery = implode(',', $ids);
    $stmt = $pdo->query("SELECT * FROM tb_produtos WHERE id IN ($inQuery)");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $quantidade = isset($carrinho[$row['id']]) ? (int)$carrinho[$row['id']] : 0;
        $preco = (float)$row['preco'];

        $row['quantidade'] = $quantidade;
        $row['subtotal'] = $quantidade * $preco;

        $produtos[] = $row;
    }

    return $produtos;
}


function getTotalCarrinho(PDO $pdo): float {
    $produtos = getCarrinhoCompleto($pdo);
    $total = 0;
    foreach ($produtos as $p) {
        $total += $p['subtotal'];
    }
    return $total;
}
