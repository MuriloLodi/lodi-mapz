<?php
function loadEnv($path = __DIR__ . '/.env') {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
        putenv(trim($name) . '=' . trim($value));
    }
}

if (session_status() === PHP_SESSION_NONE) session_start();
require 'conexao.php';

loadEnv();
$access_token = $_ENV['MERCADOPAGO_ACCESS_TOKEN'] ?? '';

$descontoPercent = isset($_SESSION['cupom_desconto']) ? (float)$_SESSION['cupom_desconto'] : 0.0;
$factor = max(0, min(1, 1 - ($descontoPercent / 100)));

$items = [];
$total = 0.0;

if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $id => $quantidade) {
        $stmt = $pdo->prepare("SELECT nome, preco FROM tb_produtos WHERE id = ?");
        $stmt->execute([$id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produto) {
            $precoOriginal   = (float)$produto['preco'];
            $precoComDesc    = round($precoOriginal * $factor, 2);

            $titulo = $produto['nome'];
            if ($descontoPercent > 0) {
                $titulo .= " (c/ desc. " . number_format($descontoPercent, 2, ',', '.') . "%)";
            }

            $items[] = [
                "title"       => $titulo,
                "quantity"    => (int)$quantidade,
                "unit_price"  => $precoComDesc,
                "currency_id" => "BRL"
            ];

            $total += $precoComDesc * (int)$quantidade;
        }
    }
} else {
    die("Carrinho vazio.");
}

$titulos = [];
foreach ($items as $item) {
    $titulos[] = $item['title'] . ' x' . $item['quantity'];
}
$tituloCompra = implode(' | ', $titulos);

$data = [
    "items" => $items, 
    "statement_descriptor" => "Lodz Mapz",
    "external_reference" => uniqid('pedido_'),
    "back_urls" => [
        "success" => "https://SEU_DOMINIO/sucesso.php",
        "failure" => "https://SEU_DOMINIO/erro.php",
        "pending" => "https://SEU_DOMINIO/pendente.php"
    ],
    "metadata" => [
        "titulo_compra" => mb_substr($tituloCompra, 0, 190),
        "desconto_percent" => $descontoPercent
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/checkout/preferences");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $access_token"
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    die('Erro CURL: ' . curl_error($ch));
}
curl_close($ch);

$preference = json_decode($response, true);


if (isset($preference['init_point'])) {
    header("Location: " . $preference['init_point']);
    exit;
} else {
    echo "<pre>";
    print_r($preference);
    echo "</pre>";
}
