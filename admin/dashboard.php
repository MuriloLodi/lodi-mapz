<?php
include "../conexao.php";
$current_page = basename($_SERVER['PHP_SELF']);
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT COUNT(*) as total FROM usuario");
$total_usuarios = $stmt->fetch()['total'];


$stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_produtos");
$total_produtos = $stmt->fetch()['total'];


$stmt = $pdo->query("SELECT COUNT(*) as total FROM tb_vendas");
$total_vendas = $stmt->fetch()['total'];


$stmt = $pdo->query("SELECT SUM(total) as total FROM tb_vendas");
$total_lucro = $stmt->fetch()['total'] ?? 0;


$stmt = $pdo->query("
    SELECT DATE_FORMAT(criado_em,'%b/%Y') as mes, SUM(total) as total,
       MIN(criado_em) as primeiro_dia
FROM tb_vendas
WHERE criado_em >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY YEAR(criado_em), MONTH(criado_em)
ORDER BY primeiro_dia

");
$vendas_mensais = $stmt->fetchAll(PDO::FETCH_ASSOC);
$labels = json_encode(array_column($vendas_mensais, 'mes'));
$data = json_encode(array_column($vendas_mensais, 'total'));
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <?php include 'includes/head.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

    </style>
</head>

<body>
    <div class="d-flex vh-100">
        <?php include 'includes/sidebar.php' ?>

        <div class="d-flex flex-column w-100">

            <div class="content">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card p-3 text-center bg-primary text-white">
                            <h5>Usuários</h5>
                            <h2><?php echo $total_usuarios; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center bg-success text-white">
                            <h5>Produtos</h5>
                            <h2><?php echo $total_produtos; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center bg-warning text-white">
                            <h5>Vendas</h5>
                            <h2><?php echo $total_vendas; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center bg-danger text-white">
                            <h5>Lucro</h5>
                            <h2>R$ <?php echo number_format($total_lucro, 2, ',', '.'); ?></h2>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card p-3">
                            <h5>Vendas Mensais</h5>
                            <canvas id="vendasChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'includes/scripts.php' ?>
            <script>
                const ctx = document.getElementById('vendasChart').getContext('2d');
                const vendasChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?= $labels ?>,
                        datasets: [{
                            label: 'Vendas (R$)',
                            data: <?= $data ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
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
        </div>
    </div>
</body>

</html>