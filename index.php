<?php include 'conexao.php' ?>
<?php
$stmt = $pdo->query("SELECT nome, img, preco FROM tb_vendidos ORDER BY id DESC LIMIT 4");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br">

<head>
  <?php include 'includes/head.php' ?>
</head>

<body>
  <div class="apresent">
    <section class="navbar mt-3">
      <div class="container nav-inner">
        <a href=""><img class="logo rounded-5" src="assets/img/logo.jpeg" alt=""></a>

        <nav>
          <ul class="nav-links">
            <li><a href="">Página inicial</a></li>
            <li><a href="">Loja</a></li>
            <li><a href="">Termos</a></li>
          </ul>
        </nav>

        <div class="nav-actions">
          <ul class="nav-actions-list">
            <li>
              <a class="flogin" href="">
                <i class="fa-solid fa-circle-user"></i>
                <span>Fazer login</span>
              </a>
            </li>
            <li>
              <a class="cart" href="">
                <i class="fa-solid fa-cart-shopping"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </section>

    <section class="container">
      <div class=" home justify-content-between d-flex">
        <div class="col-6 align-self-center align-items-center">
          <p class="title">Lodz <span>Network</span></p>
          <p class="stitle">Desde 2023, a Lodz Network transforma ideias em mapas com alma. Realismo, leveza e
            identidade exatamente como o seu projeto merece.</p>
          <p class="btn">Ver produtos</p>
        </div>
        <div class="col-4 align-self-center align-items-center">
          <ul class="d-flex redes list-unstyled">
            <li><a href=""><i class="fa-brands fa-youtube"></i></a></li>
            <li><a href=""><i class="fa-brands fa-discord"></i></a></li>
            <li><a href=""><i class="fa-brands fa-tiktok"></i></a></li>
            <li><a href=""><i class="fa-brands fa-instagram"></i></a></li>
          </ul>
        </div>
      </div>


      <div class="best mt-6">
        <div class="d-flex text-center mt-5 justify-content-between">
          <div class=" justify-content-center align-items-center">
            <i class="fa-solid fa-bolt-lightning text-default fs-2"></i>
            <h2>Entrega instantânea</h2>
            <p>Sua compra é entregue imediatamente, atribuições de funções automatizadas no Discord.</p>
          </div>
          <div>
            <i class="fa-solid fa-lock text-default fs-2"></i>
            <h2>Segurança</h2>
            <p>Proteção confiável para suas transações e dados, garantindo privacidade e confiança total.</p>
          </div>
          <div>
            <i class="fa-solid fa-headset text-default fs-2"></i>
            <h2>Suporte 24/7</h2>
            <p>Atendimento contínuo para resolver dúvidas e oferecer suporte sempre que você precisar.</p>
          </div>
        </div>
      </div>
    </section>

  </div>
  <section class="topvenda">
    <div class="container mt-3">
      <h3 class="fw-bold display-6 text-white">Mais <span class="">vendidos</span></h3>
      <div class="row mt-4">
        <?php foreach ($produtos as $p): ?>
          <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4">
              <img src="assets/img/<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($p['nome']) ?></h5>
                <p class="card-text fw-bold text-success">R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>
                <a href="#" class="btn btn-primary btn-sm">Comprar</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</body>

</html>