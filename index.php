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
    <div class="container mt-3 mb-5">

      <div class="panel mb-5">
        <img src="assets/img/entrega.png" alt="">
      </div>

      <h3 class="fw-bold display-6 text-white">Mais <span>vendidos</span></h3>
      <div class="row mt-4">
        <?php foreach ($produtos as $p): ?>
          <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 position-relative">
              <div class="position-relative">
                <img src="assets/img/<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>" class="card-img">
                <div class="overlay">
                  <button class="btn-overlay">Ver produto</button>
                </div>
              </div>
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($p['nome']) ?></h5>
                <p class="card-text fw-bold text-preco">R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>
              </div>
            </div>

          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="faq">
    <div class="container d-flex mb-5">
      <div class="col-12 col-md-4 mb-4 mb-md-0 text-center text-md-left align-self-center">
        <h2 class="fw-bold display-5  text-white">Perguntas<br><span>Frequentes</span></h2>
        <a href=""><button class="btn">Ver Produtos</button></a>
      </div>

      <div class="col-12 col-md-8">
        <div class="accordion" id="accordionExample">

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Como funcionam as encomendas?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                Você nos envia sua ideia ou necessidade — como localização, tipo de mapa, e estilo desejado — e desenvolvemos o mapeamento sob medida, mantendo sempre o foco em performance e imersão.
              </div>
            </div>
          </div>

          <div class="accordion-item mt-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Os mapas pesam muito?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                Não! Todos os nossos projetos utilizam apenas objetos nativos do MTA, o que garante um mapa extremamente leve e sem impacto no desempenho do servidor.
              </div>
            </div>
          </div>

          <div class="accordion-item mt-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Vocês fazem mapas de qualquer tipo?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                Sim! Trabalhamos com ambientações urbanas, guettos, rodoviárias, postos, ruas principais, rotatórias, favelas, interiores simples e muito mais. Tudo com realismo e coerência com o estilo visual do jogo.
              </div>
            </div>
          </div>

          <div class="accordion-item mt-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Qual o tempo médio de entrega?
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                O prazo depende da complexidade do pedido, mas buscamos sempre entregar no menor tempo possível, sem comprometer a qualidade.
              </div>
            </div>
          </div>

          <div class="accordion-item mt-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                Como posso acompanhar minha encomenda?
              </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                Cada encomenda é registrada e divulgada com um pequeno resumo no nosso canal, com prints e descrição do que foi feito. Assim, você vê nosso compromisso e evolução constante.
              </div>
            </div>
          </div>

          <div class="accordion-item mt-3">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                Como entro em contato para fazer meu pedido?
              </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                Basta nos chamar diretamente no privado ou através do nosso canal de atendimento oficial. Estamos prontos para atender você com agilidade e atenção total.
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>
</body>

</html>