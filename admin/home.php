<?php
include "../conexao.php";
$current_page = basename($_SERVER['PHP_SELF']);
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$total_usuarios = $pdo->query("SELECT COUNT(*) FROM usuario")->fetchColumn();
$total_produtos = $pdo->query("SELECT COUNT(*) FROM tb_produtos")->fetchColumn();
$total_vendas   = $pdo->query("SELECT COUNT(*) FROM tb_vendas")->fetchColumn();
$total_lucro    = $pdo->query("SELECT SUM(total) FROM tb_vendas")->fetchColumn() ?? 0;

$stmt = $pdo->query("
    SELECT 
        DATE_FORMAT(d.mes, '%b/%Y') AS mes,
        COALESCE(SUM(v.total), 0) AS total
    FROM (
        SELECT DATE_SUB(LAST_DAY(CURDATE()), INTERVAL n MONTH) AS mes
        FROM (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5) AS nums
    ) d
    LEFT JOIN tb_vendas v
        ON YEAR(v.criado_em) = YEAR(d.mes)
       AND MONTH(v.criado_em) = MONTH(d.mes)
    GROUP BY d.mes
    ORDER BY d.mes
");


$vendas_mensais = $stmt->fetchAll(PDO::FETCH_ASSOC);
$labels = json_encode(array_column($vendas_mensais, 'mes'));
$data   = json_encode(array_column($vendas_mensais, 'total'));
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <?php include 'includes/head.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div class="d-flex vh-100">
        <?php include 'includes/sidebar.php' ?>
        <div class="flex-grow-1 p-4">
            <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?>!</h2>
            <p>Painel de controle do seu site.</p>

            <div class="row g-3 mt-3">
                <div class="col-md-3">
                    <div class="card card-blue">
                        <h5>Usuários</h5>
                        <div class="stat"><?= $total_usuarios ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-green">
                        <h5>Produtos</h5>
                        <div class="stat"><?= $total_produtos ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-yellow">
                        <h5>Vendas</h5>
                        <div class="stat"><?= $total_vendas ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-red">
                        <h5>Lucro</h5>
                        <div class="stat">R$ <?= number_format($total_lucro, 2, ',', '.') ?></div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 p-3">
                <h5>Vendas Mensais (Últimos 6 meses)</h5>
                <canvas id="vendasChart" height="100"></canvas>
            </div>

            <div class="row mt-4 g-3">
                <div class="col-md-4"><a href="usuarios.php" class="btn btn-primary w-100 p-3">Gerenciar Usuários</a></div>
                <div class="col-md-4"><a href="produtos.php" class="btn btn-success w-100 p-3">Gerenciar Produtos</a></div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php' ?>
    <script>
        const ctx = document.getElementById('vendasChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Vendas (R$)',
                    data: <?= $data ?>,
                    backgroundColor: 'rgba(54,162,235,0.2)',
                    borderColor: 'rgba(54,162,235,1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>