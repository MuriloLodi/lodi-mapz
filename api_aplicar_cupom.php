<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) session_start();

require 'conexao.php';
require 'carrinho.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'msg' => 'Método não permitido.']);
        exit;
    }

    $cupom = trim($_POST['cupom'] ?? '');
    if ($cupom === '') {
        echo json_encode(['ok' => false, 'msg' => 'Informe um cupom.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM tb_cupons WHERE codigo = ? AND valido_ate >= CURDATE()");
    $stmt->execute([$cupom]);
    $cupomData = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalCarrinho = getTotalCarrinho($pdo);

    if (!$cupomData) {
        $_SESSION['cupom_desconto'] = 0;
        echo json_encode([
            'ok' => false,
            'msg' => 'Cupom inválido ou vencido.',
            'subtotal' => $totalCarrinho,
            'desconto_percent' => 0,
            'total' => $totalCarrinho
        ]);
        exit;
    }

    $descontoPercent = (float)$cupomData['desconto'];
    $_SESSION['cupom_desconto'] = $descontoPercent;

    $totalComDesconto = $totalCarrinho - ($totalCarrinho * ($descontoPercent / 100));

    echo json_encode([
        'ok' => true,
        'msg' => 'Cupom aplicado com sucesso!',
        'subtotal' => $totalCarrinho,
        'desconto_percent' => $descontoPercent,
        'total' => $totalComDesconto
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Erro ao aplicar cupom.']);
}
