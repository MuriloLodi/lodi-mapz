<?php
// trust_store.php
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <?php include 'includes/head.php' ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #333;
            margin-bottom: 5px;
        }

        .price {
            font-size: 22px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .back {
            display: inline-block;
            margin-top: 10px;
            color: #666;
            text-decoration: none;
        }

        .section {
            margin: 20px 0;
        }

        ul {
            padding-left: 20px;
        }

        .highlight {
            font-weight: bold;
        }

        iframe {
            width: 100%;
            height: 360px;
            border-radius: 8px;
            border: none;
            margin-top: 20px;
        }

        .produtos-destaque {
            margin-top: 30px;
        }

        .produtos-destaque h2 {
            margin-bottom: 10px;
        }

        .produto-card {
            background: #f2f2f2;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php' ?>
    <div class="container">

        <img src="" alt="">
        <div class="row" style="background-image: linear-gradient(to right, rgba(15, 26, 44, 0.8) 20%, rgba(15, 26, 44, 0) 50%, rgba(15, 26, 44, 0.8) 80%), url(https://i.imgur.com/VBVzWMc.png);    background-size: cover;
    background-position: center 60%;
    background-repeat: no-repeat;
    color: rgb(255, 255, 255);
    padding: 30px;
    border-radius: 10px;
}">     
<div class="col">
        <h1>Hospital LS</h1>
        <p class="price">R$ 40</p>
        </div>
        <div>
        <a href="#" class="btn">➕ ADICIONAR AO CARRINHO</a><br>
        <a href="#" class="back">⬅ Voltar aos Produtos</a>
        </div>
        </div>

        <div class="section">
            <p><span class="highlight">Peso:</span> 2,87 MB</p>
            <ul>
                <li>4 Consultórios</li>
                <li>1 Recepção</li>
                <li>4 Salas de atendimentos</li>
                <li>2 Helipontos</li>
                <li>Sistema de Elevador</li>
                <li>Área para Ambulância</li>
            </ul>
            <p><strong>Importante:</strong> nossos produtos funcionam somente no MTA:SA. Não são compatíveis com GTA SA
                ou SA:MP.</p>
        </div>

        <div class="section">
            <h2>Detalhes do Produto</h2>
            <ul>
                <li>Proteção no Dff e Col</li>
                <li>Proteção por IP por Módulo</li>
                <li>Txd liberada para alterações</li>
            </ul>
        </div>

        <div class="produtos-destaque">
            <h2>Produtos Destaques</h2>
            <div class="produto-card">Centro Comercial LV - <strong>R$ 35</strong></div>
            <div class="produto-card">Moto Club - <strong>R$ 30</strong></div>
            <div class="produto-card">Praça 2.0 - <strong>R$ 30</strong></div>
        </div>

        <iframe src="https://www.youtube.com/embed/WFKEXjh_aXo" allowfullscreen></iframe>
    </div>

        <?php include 'includes/footer.php' ?>
    <?php include 'includes/scripts.php' ?>
</body>

</html>